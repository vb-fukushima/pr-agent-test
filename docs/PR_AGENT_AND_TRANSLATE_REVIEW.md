# PR-Agent 導入とレビューコメント翻訳のドキュメント

本ドキュメントは、GitHub の PR で AI コードレビュー（PR-Agent / Qodo Merge）を利用し、そのコメントをベトナム語・日本語に自動翻訳する仕組みの導入内容をまとめたものです。Backlog 等への記録用です。

---

## 1. 概要

- **PR-Agent（Qodo Merge）**: GitHub App として導入し、PR に対して AI がコードレビューコメントを投稿する。
- **翻訳ワークフロー**: PR-Agent の英語コメントに対して、Gemini API でベトナム語・日本語に翻訳し、1 件の返信として投稿する。
- **配置**: `.github/workflows/translate-review.yml` を追加する。**GitHub App 利用時は `.github/workflows/code-quality.yml` は不要**（PR-Agent 用の workflow は不要）。既存でリント・テスト用の workflow がある場合は、それと translate-review.yml は別々に動作し併存可能。

---

## 2. 導入したファイル

### 2.1 `.github/workflows/translate-review.yml`

| 項目 | 内容 |
|------|------|
| **役割** | PR-Agent（qodo-code-review[bot]）のコメントを検知し、同じコメントに「ベトナム語＋日本語」の翻訳を 1 件の返信で投稿する。 |
| **トリガー** | `issue_comment`（created）、`pull_request_review_comment`（created） |
| **対象** | コメント投稿者が `qodo-code-review[bot]` である場合、または本文に `<!-- pr-agent` が含まれる場合。 |
| **翻訳対象外** | 本文に **「Looking for bugs?」** が含まれるコメントは翻訳しない。PR-Agent の「解析中」用プレースホルダーであり、解析終了で消えるため、翻訳だけ残るのを防ぐ。 |
| **翻訳結果の形式** | 1 件の返信内で「🇻🇳 Tiếng Việt（上）→ 区切り線（---）→ 🇯🇵 日本語（下）」で記載。コードブロック・インラインコード・マークダウンは翻訳せずそのまま保持。 |
| **利用 API** | Google Gemini（gemini-2.5-flash）。RPM 制限対策のリトライあり。 |

---

## 3. 必要な設定

### 3.1 GitHub Secrets（必須）

リポジトリの **Settings → Secrets and variables → Actions → New repository secret** で以下を登録する。

| Secret 名 | 説明 |
|-----------|------|
| **GEMINI_API_KEY** | Google AI Studio（Generative Language API）で発行した API キー。翻訳に使用。 |

**特記事項**: API キーは VeryBest が用意する。

### 3.2 GitHub App（PR-Agent）の導入

- [Qodo Merge](https://www.qodo.ai/) 等から **PR-Agent の GitHub App** をインストールする。
- 対象の運用リポジトリを選択して有効化する。
- PR がオープン・更新されると、`qodo-code-review[bot]` がレビューコメントを投稿する（デフォルトは英語）。

### 3.3 オプション: `.pr_agent.toml`

PR-Agent の挙動（言語指定など）を変えたい場合のみ、リポジトリルートに `.pr_agent.toml` を置く。  
App 運用時は必須ではなく、未設定の場合は英語でレビューされ、上記ワークフローが翻訳を付ける。

---

## 4. 運用上の注意

- **ワークフローの配置**: `translate-review.yml` は **デフォルトブランチ（多くの場合 main）に存在する必要がある**。PR のコメント発火時に、ベースブランチのワークフローが参照される。
- **PR-Agent と workflow**: GitHub App で PR-Agent を利用する場合、**`.github/workflows/code-quality.yml` など PR-Agent 用の workflow は不要**。必要なのは翻訳用の `translate-review.yml` のみ。既存のリント・テスト用 workflow がある場合は、translate-review.yml と併存可能で競合しない。
- **フォーク PR**: 現状の条件では、同一リポジトリの PR のコメントにのみ反応する想定。フォークからの PR で翻訳も行いたい場合は、ワークフローの `if` 条件や権限の見直しが必要になる場合がある。
- **Bot 名**: 別の PR-Agent 系 App（別の bot 名）を利用する場合は、`translate-review.yml` の `if` 内の `qodo-code-review[bot]` をその bot 名に合わせて変更する。

---

## 5. 導入チェックリスト（実運用リポジトリ用）

- [ ] `.github/workflows/translate-review.yml` を配置し、デフォルトブランチにマージ済みであること
- [ ] GitHub Secrets に `GEMINI_API_KEY` を登録済みであること
- [ ] PR-Agent（Qodo Merge）の GitHub App を対象リポジトリにインストール済みであること
- [ ] （任意）`.pr_agent.toml` で言語等をカスタマイズする場合は、リポジトリルートに配置すること

---

## 6. 更新履歴（例）

| 日付 | 内容 |
|------|------|
| YYYY-MM-DD | 初版作成。PR-Agent 導入と translate-review ワークフローの仕様を記載。 |

---

以上。
