<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>受付 | 小鳥宿</title>
        <style>
            :root {
                color-scheme: light;
                font-family: "Hiragino Kaku Gothic ProN", "Yu Gothic", Meiryo, sans-serif;
                color: #24332d;
                background: #f5f7f2;
            }

            * { box-sizing: border-box; }

            body {
                min-height: 100vh;
                margin: 0;
                background: linear-gradient(135deg, #f5f7f2 0%, #e8f0e8 100%);
            }

            .page {
                display: grid;
                place-items: center;
                min-height: 100vh;
                padding: 24px;
            }

            .card {
                width: min(100%, 640px);
                padding: clamp(32px, 8vw, 64px);
                text-align: center;
                background: #fff;
                border: 1px solid #dce7dd;
                border-radius: 24px;
                box-shadow: 0 20px 50px rgba(36, 51, 45, .1);
            }

            .eyebrow {
                margin: 0 0 12px;
                color: #62836b;
                font-size: .85rem;
                font-weight: 700;
                letter-spacing: .16em;
            }

            h1 {
                margin: 0;
                font-size: clamp(2rem, 6vw, 3.25rem);
                letter-spacing: .08em;
            }

            .lead {
                margin: 24px auto 0;
                max-width: 32rem;
                color: #5d6d63;
                line-height: 1.9;
            }

            .status {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                margin: 32px 0 24px;
                padding: 8px 14px;
                color: #4d6d56;
                background: #edf6ee;
                border-radius: 999px;
                font-size: .9rem;
            }

            .status::before {
                width: 8px;
                height: 8px;
                content: "";
                background: #79a582;
                border-radius: 50%;
            }

            .button {
                display: inline-block;
                padding: 14px 28px;
                color: #fff;
                background: #62836b;
                border-radius: 10px;
                font-weight: 700;
                text-decoration: none;
                transition: background .2s ease, transform .2s ease;
            }

            .button:hover {
                background: #4d6d56;
                transform: translateY(-1px);
            }

            .footer {
                margin: 32px 0 0;
                color: #8a998e;
                font-size: .8rem;
            }
        </style>
    </head>
    <body>
        <main class="page">
            <section class="card" aria-labelledby="reception-title">
                <p class="eyebrow">KOTORI YADO</p>
                <h1 id="reception-title">受付</h1>
                <p class="lead">
                    小鳥宿へようこそ。<br>
                    こちらからチェックイン・チェックアウトなどのお手続きをご案内します。
                </p>
                <p class="status">受付システム準備中</p>
                <div>
                    <a class="button" href="{{ url('/') }}">トップページへ戻る</a>
                </div>
                <p class="footer">ご不明な点がございましたら、スタッフまでお声がけください。</p>
            </section>
        </main>
    </body>
</html>
