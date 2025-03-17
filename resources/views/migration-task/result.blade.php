<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>検証結果</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .result-container {
            margin-top: 100px;
            text-align: center;
        }
        .result-success {
            font-size: 100px;
            font-weight: bold;
            color: #28a745;
        }
        .result-error {
            font-size: 100px;
            font-weight: bold;
            color: #dc3545;
        }
        .message {
            font-size: 24px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container result-container">
        @if($result)
            <div class="result-success">クリア</div>
        @else
            <div class="result-error">NG</div>
        @endif

        <div class="message">
            {{ $message }}
        </div>

        <div class="mt-5">
            <a href="{{ route('migration-task.index') }}" class="btn btn-primary">戻る</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
