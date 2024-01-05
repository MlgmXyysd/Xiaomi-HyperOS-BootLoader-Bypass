# Xiaomi HyperOS BootLoader Bypass

![Version: 1.0](https://img.shields.io/badge/Version-1.0-brightgreen?style=for-the-badge) [![English](https://img.shields.io/badge/English-brightgreen?style=for-the-badge)](README.md) [![日本語](https://img.shields.io/badge/日本語-brightgreen?style=for-the-badge)](README-ja.md)

利用漏洞绕过小米 HyperOS 对 BootLoader 解锁账户绑定限制社区等级的 PoC。

您可随时向本项目提出改进方案 :)

## 💘 php-adb

本项目使用了 [php-adb](https://github.com/MlgmXyysd/php-adb) 运行库。

## ☕ 支持开发

✨ 如果您喜欢我的项目，可以请我喝咖啡:

 - [爱发电](https://afdian.net/@MlgmXyysd)
 - [PayPal](https://paypal.me/MlgmXyysd)
 - [Patreon](https://www.patreon.com/MlgmXyysd)

## ⚠️ 警告

解锁 BootLoader 后，你可能会遇到以下情况：

- 软件或硬件无法正常工作，甚至永久性损坏。
- 设备中存储的数据丢失。
- 信用卡被盗刷，或遭受其他经济损失。

如果您遇到上述任何情况，您应该自己承担所有责任，因为这是您在解锁 BootLoader 时可能遇到的风险。这显然不能涵盖所有风险。我们已经警告过您了。

- 保修丢失。根据小米提供的免责条款，这不仅是基础三包，您购买的一些额外延保（如 Mi Care 或碎屏险）也可能会丢失。
- 像 Samsung Knox 那样的硬件级熔断。TEE 相关功能将永久损坏。除更换主板外，无法恢复。
- 刷入第三方系统后出现功能异常，这可能是因为内核源代码闭源引起。
- 设备或账号因为解锁 BootLoader 被小米封禁。

如果您遇到上述任何情况，请您自认倒霉。自从小米限制解锁 BootLoader 后，小米就一直在违背"极客"精神，甚至违背了 GPL。小米对 BootLoader 解锁的限制是无穷尽的，作为开发者，我们对此无能为力。

## 📲 前置要求

- 一个有效的设备:
  - 一个未被封禁\*的小米、红米或 POCO 设备。
  - 设备正在运行官方版 HyperOS。
  - (2023/11/23 更新) 您的设备不会被小米强制验证账户资格。
- 一个有效的 SIM 卡:
  - \* 无法使用 SIM 卡的平板电脑除外。
  - SIM 卡不得处于停机或无服务状态。
  - SIM 卡需要能够连接到互联网。
  - 每张有效 SIM 卡在三个月内只能解锁 2 台设备。
- 一个有效的小米账号:
  - 一个未被封禁\*的小米账号。
  - 每个账号一个月只能解锁一部手机，一年只能解锁三部手机。
- 您已阅读并理解上述 [警告](#%EF%B8%8F-警告)。

- \* 根据小米提供的解锁说明，某些账号和设备将被禁止使用解锁工具，这被称为"风控"。

## ⚙️ 使用教程

1. 从 [官方网站](https://www.php.net/downloads) 下载并安装适用于您操作系统的 PHP 8.0+。
2. 在 `php.ini` 中启用 OpenSSL 和 Curl 扩展。（如果脚本未正常工作，请将 `extension_dir` 设置为 PHP 的 `ext` 文件夹路径。）
3. 将 [php-adb](https://github.com/MlgmXyysd/php-adb) 中的 `adb.php` 放到目录中。
4. 下载 [platform-tools](https://developer.android.com/studio/releases/platform-tools)，并将其放入 `libraries`。*注意：Mac OS 需要将 `adb` 重命名为 `adb-darwin`。
5. 打开终端，使用 PHP 解释器执行 [脚本](../bypass.php)。

- p.s. Releases 已将所需文件和一键脚本打包。

6. 多次点击`设置 - 关于手机 - MIUI 版本`启用`开发者选项`。
7. 在`设置 - 附加设置 - 开发者选项`中启用`OEM 解锁`、`USB 调试`和`USB 调试（安全设置）`。
8. 登录一个_有效_\*的小米账号。
9. 通过有线方式将设备连接到电脑。
10. 选中`始终允许来自此计算机的调试`，然后单击`确定`。

- \* 请参阅上文的 "[前置要求](#-前置要求)"。

11. 等待并按脚本提示操作。
12. 绑定成功后，您可以使用 [官方解锁工具](https://www.miui.com/unlock/index.html) 查看需要等待的时间。
13. 在等待期间，请正常使用设备，保持 SIM 卡插入，不要登出小米账号或关闭"查找我的手机"，不要重新绑定设备，直到成功解锁。设备将每隔一段时间自动向服务器发送 `HeartBeat` 数据包。

## 📖 漏洞分析

- 维修中...

## 🔖 FAQ

- Q: 为什么解锁工具仍然提醒我等待 168/360（或更长）小时？
  - A: 根据原理，该 PoC 只绕过了小米为 HyperOS 额外添加的限制。您仍然需要遵循 MIUI 的限制。

- Q: 设备显示 "验证失败，请稍后再试"。
  - A: 这是正常现象，设备端的绑定请求已被脚本拦截。实际绑定结果以脚本提示为准。

- Q: 绑定失败，错误代码为 `401`。
  - A: 您的小米账号凭据已过期，您需要在设备中登出账号并重新登录。

- Q: 绑定失败，错误代码为 `20086`。
  - A: 您的设备凭据已过期，您可能需要重新启动设备。

- Q: 绑定失败，错误代码为 `20090` 或 `20091`。
  - A: 设备的 `Security Device Credential Manager` 功能已损坏，请联系售后服务寻求支持。

- Q: 绑定失败，错误代码为 `30001`。
  - A: 您的设备已被小米强制验证账户资格。小米早就抛弃了"极客"精神，我们对此无能为力。

- Q: 绑定失败，错误代码为 `86015`。
  - A: 服务器拒绝了本次绑定请求，请重试。

## ⚖️ 协议

无许可证，您只被允许使用本项目。未经许可，不得删除或更改本软件的所有版权（以及链接等）。本项目所有权利归 [MeowCat Studio](https://github.com/MeowCat-Studio)、[Meow Mobile](https://github.com/Meow-Mobile) 和 [NekoYuzu](https://github.com/MlgmXyysd) 所有。
