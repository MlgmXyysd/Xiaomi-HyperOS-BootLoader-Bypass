# requirements:
# pip install adbutils pycryptodome
import base64
import json
import re
from time import sleep
from urllib.parse import urlencode
from urllib.request import urlopen, Request

from adbutils import adb
from Crypto.Cipher import AES
from Crypto.Hash import HMAC, SHA1


USE_GLOBAL = False
API = (
    "https://unlock.update.intl.miui.com"
    if USE_GLOBAL
    else "https://unlock.update.miui.com"
)
SIGN_KEY = b"10f29ff413c89c8de02349cb3eb9a5f510f29ff413c89c8de02349cb3eb9a5f5"
DATA_PASS = b"20nr1aobv2xi8ax4"
DATA_IV = b"0102030405060708"

VERSION = "1.0"


def main():
    # /====================================
    print("Starting ADB server...")

    devices = adb.device_list()

    while len(devices) != 1:
        if not devices:
            print("Waiting for device connection...")
        else:
            print(
                "Only one device is allowed to connect, disconnect others to continue. Current number of devices: ",
                len(devices),
            )
        sleep(1)
        devices = adb.device_list()

    device = devices[0]

    print("Processing device ", device.serial)

    device.shell("logcat --clear")
    device.shell("svc data enable")

    # /====================================
    print("Finding BootLoader unlock bind request...")

    focus = getCurrentActivity(device)
    if focus[0] != "com.android.settings":
        if focus[0] != "NotificationShade":
            device.shell(
                "am start -a android.settings.APPLICATION_DEVELOPMENT_SETTINGS"
            )
    else:
        if focus[1] != "com.android.settings.bootloader.BootloaderStatusActivity":
            device.shell(
                "am start -a android.settings.APPLICATION_DEVELOPMENT_SETTINGS"
            )

    # /====================================
    print("Now you can bind account in the developer options.")

    args = headers = None

    logcat_stream = device.shell("logcat *:S CloudDeviceStatus:V", stream=True)
    fsrc = logcat_stream.conn.makefile("r", encoding="UTF-8", errors="replace")
    while True:
        line = fsrc.readline()
        match = re.search(r"CloudDeviceStatus: (\w+):(.*)", line)
        if match:
            print(match.string)
            if match[1] == "args":
                args = match[2].strip()
                device.shell("svc data disable")
            elif match[1] == "headers":
                headers = match[2].strip()
                print(
                    "Account bind request found! Let's block it."
                )  # via ^ svc data disable
                break

    # cleanup
    fsrc.close()
    logcat_stream.close()

    # /====================================
    print("Refactoring parameters...")

    args = json.loads(decryptData(args))
    args["rom_version"] = args["rom_version"].replace("V816", "V14")
    args = json.dumps(args, separators=(",", ":"))
    sign = signData(args)

    headers = decryptData(headers).decode("ascii")

    cookie = {}
    cookie_match = re.search(r"Cookie=\[(.*)]", headers)

    cookie = cookie_match[1] if cookie_match else None

    # /====================================
    print("Sending POST request...")

    req = Request(
        f"{API}/v1/unlock/applyBind",
        data=urlencode(
            {"data": args, "sid": "miui_sec_android", "sign": sign}
        ).encode(),
        headers={"Content-Type": "application/x-www-form-urlencoded", "Cookie": cookie},
        method="POST",
    )
    res = urlopen(req)
    if res.status != 200:
        raise "request failed!"

    # /====================================
    device.shell("svc data enable")

    content = json.loads(res.read())
    code = content["code"]
    if code == 0:
        print("Target account:", content["data"]["userId"])
        print("Account bound successfully, wait time can be viewed in the unlock tool.")
    elif code == 401:
        print(
            "Account credentials have expired, re-login to your account in your phone. (401)"
        )
    elif code == 20086:
        print("Device credentials expired. (20086)")
    elif code == 30001:
        print(
            "Binding failed, this device has been forced to verify the account qualification by Xiaomi. (30001)"
        )
    elif code == 86015:
        print("Fail to bind account, invalid device signature. (86015)")
    else:
        print(f'{content["descEN"]} ({content["code"]})')


def getCurrentActivity(device) -> tuple:
    """Get the current device activity

    Args:
        device: the device to query from

    Returns:
        tuple
    """
    output = device.shell("dumpsys window | grep mCurrentFocus")
    if not output:
        return (False, False)
    match = re.search(r"Window\{(.+?)\}", output)
    if not match:
        return (False, False)
    activity = match[1].rsplit(" ", 1)[1].split("/")
    return (activity[0], activity[1] if len(activity) > 1 else False)


def signData(data: str) -> str:
    """Sign data using HMAC SHA-1

    Args:
        data (str): Data to sign

    Returns:
        str: Signed hash
    """
    h = HMAC.new(SIGN_KEY, digestmod=SHA1)
    h.update(
        f"POST\n/v1/unlock/applyBind\ndata={data}&sid=miui_sec_android".encode("ascii")
    )
    return h.hexdigest().lower()


def decryptData(data: str) -> str:
    """Decrypt data using AES/CBC/PKCS5Padding

    Args:
        data (str): Data to decrypt

    Returns:
        str: Decrypted data
    """
    data = base64.b64decode(data)
    cipher = AES.new(key=DATA_PASS, mode=AES.MODE_CBC, iv=DATA_IV)
    decrypted = cipher.decrypt(data)

    unpad_PKCS5 = lambda s: s[0 : -s[-1]]
    return unpad_PKCS5(decrypted)


if __name__ == "__main__":
    main()
