# `initialize` メソッドのいろんな記述方法

---

## パターン１：いつもの書き方（引数で直接受け取る）

`initialize` の中でセッターの役割も担っている。インスタンス生成と同時にデータをセットする。

```ruby
def initialize(author, title, content)
  @author = author
  @title = title
  @content = content
end

article = Article.new("阿部", "Rubyの素晴らしさについて", "Awesome Ruby!")
```

---

## パターン２：`initialize` を空にして、後からセッターでセットする

`initialize` をインスタンス生成のトリガー（`.new`）だけの役割にして、データのセットは後からセッターを呼び出して行う。

- セッターを呼ぶためにはゲッターも必要なので、両方定義する。
- `initialize` が空なので引数を受け取る必要がない。

```ruby
def author
  @author
end

def author=(new_author)
  @author = new_author
end

def initialize
end

article = Article.new

article.author  = "阿部"
article.title   = "Rubyの素晴らしさ"
article.content = "Awesome Ruby!"
```

---

## パターン３：ハッシュをまとめて `initialize` に渡す

セッターは `initialize` から分離させた上で、セットするデータをハッシュ（`params`）としてひとまとめにして渡す。  
`initialize` の中で `self.セッター = ハッシュ[:キー]` の形でセッターを呼び出してセットする。

```ruby
def author=(new_author)
  @author = new_author
end

def initialize(attributes = {})
  self.author  = attributes[:author]
  self.title   = attributes[:title]
  self.content = attributes[:content]
end

article_params = { author: "阿部", title: "Rubyの素晴らしさ", content: "Awesome Ruby!" }
article = Article.new(article_params)
```

### Railsでの実際の使われ方

Railsのコントローラーがまさにこのパターン。`article_params` が返すハッシュを `Article.new` に渡している。

```ruby
class ArticlesController < ApplicationController
  def create
    @article = Article.new(article_params)
    # ...
  end

  private

  def article_params
    params.require(:article).permit(:author, :title, :content)
  end
end
```

`Article.new(article_params)` が呼ばれると `initialize` が実行され、`article_params` が返すハッシュが `attributes` として渡される。  
`initialize` 内で `self.author = attributes[:author]` のようにセッターを呼び出して、ハッシュから値を取り出してインスタンス変数にセットしている。

---

## ３パターンのまとめ

| パターン | `initialize` の役割 | データをセットするタイミング |
|:---------|:--------------------|:-----------------------------|
| １ | 引数を受け取って直接代入 | `.new` と同時 |
| ２ | 空（トリガーのみ） | `.new` の後にセッターを個別に呼ぶ |
| ３ | ハッシュからセッターを呼び出す | `.new` にハッシュを渡すと同時（Railsスタイル） |
