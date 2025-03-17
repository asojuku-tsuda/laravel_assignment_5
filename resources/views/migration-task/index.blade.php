<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>マイグレーションタスク</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .table-definition {
            font-size: 14px;
            margin-bottom: 30px;
        }
        .verify-btn {
            margin-top: 15px;
        }
        pre {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">マイグレーションタスク</h1>

        <div class="row">
            <div class="col-12">
                <div class="alert alert-info">
                    <p>以下のテーブル定義に基づいてマイグレーションファイルを作成してください。マイグレーションを実行した後、「検証」ボタンをクリックして結果を確認してください。</p>
                </div>
            </div>
        </div>

        <div class="row mb-5">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h2>テーブル定義</h2>
                    </div>
                    <div class="card-body">
                        @foreach($tables as $tableName => $tableDefinition)
                            <h3>{{ $tableName }} テーブル</h3>
                            <table class="table table-bordered table-definition">
                                <thead class="table-dark">
                                    <tr>
                                        <th>カラム名</th>
                                        <th>データ型</th>
                                        <th>制約</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tableDefinition['columns'] as $column)
                                        <tr>
                                            <td>{{ $column['name'] }}</td>
                                            <td>{{ $column['type'] }}</td>
                                            <td>
                                                @if(isset($column['primary']) && $column['primary'])
                                                    <span class="badge bg-primary">主キー</span>
                                                @endif

                                                @if(!$column['nullable'])
                                                    <span class="badge bg-warning text-dark">NOT NULL</span>
                                                @endif

                                                @if(isset($column['unique']) && $column['unique'])
                                                    <span class="badge bg-info">ユニーク</span>
                                                @endif

                                                @if(isset($column['default']))
                                                    <span class="badge bg-secondary">デフォルト: {{ $column['default'] }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <a href="{{ route('migration-task.verify', $tableName) }}" class="btn btn-primary verify-btn">{{ $tableName }} テーブルを検証</a>
                            <hr class="my-4">
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
