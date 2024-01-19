# Xiaomi HyperOS BootLoader Bypass

![Version: 1.0](https://img.shields.io/badge/Version-1.0-brightgreen?style=for-the-badge) [![English](https://img.shields.io/badge/English-brightgreen?style=for-the-badge)](README.md) [![中文文档](https://img.shields.io/badge/中文文档-brightgreen?style=for-the-badge)](README-zh.md)

脆弱性を悪用し、コミュニティレベルのアカウントバインド制限をバイパスして、Xiaomi HyperOS の BootLoader のロックを解除できる PoC。

> [!NOTE]
> Pull reqeust で このプロジェクトの改善を提案できます :)

## 💘 php-adb

このプロジェクトでは [php-adb](https://github.com/MlgmXyysd/php-adb) ライブラリを使用しています。

## ☕ コーヒーを奢る

✨ このプロジェクトが気に入ったら、以下の方法でコーヒーを奢ってください：

 - [AFDIAN](https://afdian.net/@MlgmXyysd)
 - [PayPal](https://paypal.me/MlgmXyysd)
 - [Patreon](https://www.patreon.com/MlgmXyysd)

## ⚠️ 警告

BootLoader のロックを解除した後、以下の問題が発生する可能性があります：

- ソフトウェアの異常動作／ハードウェアの損傷
- デバイスに保存されているデータの損失
- クレジットカードの盗難、またはその他の経済的損失

> [!CAUTION]
> 上記のいずれかが発生した場合は、BootLoader のロックを解除する際に発生する可能性があるリスクであるため、すべての責任は自分で負う必要があります。  
これは明らかにすべてのリスクを保障するものではありません。**警告しましたからね**。

- 保証適用外  
  基本保証だけでなく、購入した追加の延長保証の一部 (Mi Care や画面破損の保証など) も、Xiaomi が提供する除外規定に従って失われる可能性があります。
- TEE 関連の機能は永久に使用不可能です。  
  Samsung Knox のようにハードウェア レベルで自己破壊するため、マザーボードを交換する以外に復旧方法はありません。
- ソースコード非公開のカーネルが原因で、サードパーティ システムをフラッシュした後の機能異常
- BootLoader のロックを解除すると、デバイスまたはアカウントが BAN されます。

> [!WARNING]
> 上記のいずれかが発生した場合は、運が悪いと思ってください。  
Xiaomi が BootLoader のロック解除を制限して以来、Xiaomi の「オタク」精神、さらには GPL にも違反しています。
> BootLoader のロック解除に対する Xiaomi の制限は無限であり、開発者としてそれに対してできることは何もありません。

## 📲 アンロック要件

- 対象デバイス：
  - BAN されていない[^1] Xiaomi、Redmi、または POCO デバイス
  - デバイスが HyperOS の公式バージョンを実行している
  - (2023/11/23 に追記) デバイスは Xiaomi によってアカウントの資格確認を強制されない
- 有効な SIM カード：
  - SIM カードが使用できないタブレットを除く[^1]
  - SIM カードのサービスが使用不能になっていない
  - SIM カードはモバイルネットワークにアクセスできる
  - 有効な SIM カード毎に、３か月以内に ２台のデバイスのロックを解除できます。
- 有効な Xiaomi アカウント
  - BAN されていない[^1] Xiaomi アカウント
  - 各アカウントでロックを解除できるのは、１ヶ月で１台、１年で３台のみです。
- 上記の [警告](#%EF%B8%8F-警告) を読み、理解したものとします。

[^1]: Xiaomi が提供するロック解除手順によると、特定のアカウントとデバイスはロック解除ツールの使用を禁止されます。これは「リスク管理」と呼ばれています。

## ⚙️ 使用方法
1. [公式サイト](https://www.php.net/downloads) からシステムに PHP 8.0+ をダウンロードしてインストールします。
2. `php.ini` で OpenSSL と Curl 拡張機能を有効にします。  
  (スクリプトが機能しない場合は、`extension_dir` を PHP の `ext` ディレクトリに設定してください。)
3. [php-adb](https://github.com/MlgmXyysd/php-adb) の `adb.php` をディレクトリに配置します。
4. [platform-tools](https://developer.android.com/studio/releases/platform-tools?hl=ja#downloads) をダウンロードして`libraries` に展開します。
  ※注意: Mac OS では、`adb` の名前を `adb-darwin` に変更する必要があります。
5. ターミナルを開き、PHP インタープリターを使用して[スクリプト](../bypass.php)を実行します。

- P.S. [Releases](https://github.com/MlgmXyysd/Xiaomi-HyperOS-BootLoader-Bypass/releases/latest) には、必要なファイルとクイック実行スクリプトが同梱されています。

6. `設定`→`デバイス情報`→`MIUIバージョン`を７回以上連続でタッチして`開発者向けオプション`を有効にします。
7. `設定`→`追加設定`→`開発者向けオプション`で、`OEMロック解除`、`USBデバッグ`、`USBデバッグ (セキュリティ設定)` を有効にします。
8. **有効な**[^1] Xiaomi アカウントでログインします。
9. デバイスを優先でPCに接続します。
10. `このPCからのUSBデバイスを常に許可する` にチェックを入れてから `OK` を押します。

- ※ 上記の["アンロック要件"](#-アンロック要件)を確認して下さい。

11. スクリプトの指示に従い待機します。
12. バインドが成功したら、[公式のロック解除ツール](https://www.miui.com/unlock/index.html)を使用して、解除が可能になるまでの待機時間を確認できます。
13. 待機時間中は、デバイスを通常どおり使用し、SIM カードを挿入したままにし、Xiaomi アカウントからログアウトしたり、`デバイスを探す`をオフにしたりせず、ロックが正常に解除されるまでデバイスを再バインドしないでください。  
  デバイスは定期的に`HeartBeat`パケットをサーバーに自動的に送信します。

## 📖 回避策

- 準備中...

## 🔖 FAQ

- Q: ロック解除ツールが依然として 168/360 (またはそれ以上) 時間待つように要求するのはなぜですか？
  - A: 原則として、この PoC は、Xiaomi が HyperOS に対して追加した追加の制限を回避するだけです。  
    MIUI の制限に従う必要があります。

- Q: デバイスに `Couldn't verify, wait a minute or two and try again` と表示される。
  - A: これは正常であり、デバイス側のバインド要求はスクリプトによってブロックされます。  
    実際のバインド結果は、スクリプトプロンプトの影響を受けます。

- Q: エラーコード `401` でバインドに失敗しました。
  - A: Xiaomi アカウントの認証情報の有効期限が切れているため、デバイスからログアウトして再度ログインする必要があります。

- Q: エラーコード `20086` でバインドに失敗しました。
  - A: デバイスの認証情報の有効期限が切れているため、デバイスを再起動する必要があります。

- Q: エラーコード `20090` または `20091` でバインドに失敗しました。
  - A: デバイスの `Security Device Credential Manager` 機能が破損しています。  
    サポートについてはアフターサービスにお問い合わせください。

- Q: エラーコード `30001` でバインドに失敗しました。
  - A: デバイスは、Xiaomi によってアカウントの資格確認を強制されました。  
    Xiaomi はずっと前に「オタク」の精神を失っており、それについて私たちにできることは何もありません。

- Q: エラーコード `86015` でバインドに失敗しました。
  - A: サーバーがバインド要求を拒否しました。もう一度試してください。

## ⚖️ ライセンス

ライセンスがない場合、このプロジェクトの使用のみが許可されます。  
このソフトウェア（およびリンクなど）のすべての著作権を許可なく削除または変更することはできません。  
このプロジェクトのすべての権利は、[MeowCat Studio](https://github.com/MeowCat-Studio)、[Meow Mobile](https://github.com/Meow-Mobile)、[NekoYuzu](https://github.com/MlgmXyysd) に帰属します。
