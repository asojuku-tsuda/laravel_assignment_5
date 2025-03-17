<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MigrationVerifierController extends Controller
{
    public function index()
    {
        return view('migration-task.index', [
            'tables' => [
                'animes' => $this->getAnimesTableDefinition(),
                'ghibli_films' => $this->getGhibliFilmsTableDefinition(),
                'sports' => $this->getSportsTableDefinition()
            ]
        ]);
    }

    public function verify($table)
    {
        if (!Schema::hasTable($table)) {
            return view('migration-task.result', [
                'result' => false,
                'message' => "テーブル {$table} が存在しません。"
            ]);
        }

        $columns = DB::select("SHOW COLUMNS FROM {$table}");
        $columnInfo = [];

        foreach ($columns as $column) {
            $columnInfo[$column->Field] = [
                'type' => $column->Type,
                'null' => $column->Null,
                'key' => $column->Key,
                'default' => $column->Default,
                'extra' => $column->Extra,
            ];
        }

        // 実際のテーブル構造と期待する構造を比較
        $expected = $this->getExpectedStructure($table);
        $isValid = $this->compareStructures($columnInfo, $expected);

        return view('migration-task.result', [
            'result' => $isValid,
            'message' => $isValid
                ? "テーブル {$table} の構造は正しいです！"
                : "テーブル {$table} の構造に問題があります。"
        ]);
    }

    private function getAnimesTableDefinition()
    {
        return [
            'columns' => [
                ['name' => 'id', 'type' => 'bigint unsigned', 'nullable' => false, 'primary' => true],
                ['name' => 'title', 'type' => 'varchar', 'nullable' => false],
                ['name' => 'genre', 'type' => 'varchar', 'nullable' => false],
                ['name' => 'episodes', 'type' => 'int', 'nullable' => false],
                ['name' => 'aired_from', 'type' => 'date', 'nullable' => true],
                ['name' => 'aired_to', 'type' => 'date', 'nullable' => true],
                ['name' => 'rating', 'type' => 'decimal', 'nullable' => true],
                ['name' => 'created_at', 'type' => 'timestamp', 'nullable' => true],
                ['name' => 'updated_at', 'type' => 'timestamp', 'nullable' => true],
            ]
        ];
    }

    private function getGhibliFilmsTableDefinition()
    {
        return [
            'columns' => [
                ['name' => 'id', 'type' => 'bigint unsigned', 'nullable' => false, 'primary' => true],
                ['name' => 'title', 'type' => 'varchar', 'nullable' => false],
                ['name' => 'director', 'type' => 'varchar', 'nullable' => false],
                ['name' => 'release_year', 'type' => 'int', 'nullable' => false],
                ['name' => 'runtime', 'type' => 'int', 'nullable' => false],
                ['name' => 'box_office', 'type' => 'bigint', 'nullable' => true],
                ['name' => 'is_classic', 'type' => 'tinyint(1)', 'nullable' => false, 'default' => '0'],
                ['name' => 'created_at', 'type' => 'timestamp', 'nullable' => true],
                ['name' => 'updated_at', 'type' => 'timestamp', 'nullable' => true],
            ]
        ];
    }

    private function getSportsTableDefinition()
    {
        return [
            'columns' => [
                ['name' => 'id', 'type' => 'bigint unsigned', 'nullable' => false, 'primary' => true],
                ['name' => 'name', 'type' => 'varchar', 'nullable' => false],
                ['name' => 'type', 'type' => 'varchar', 'nullable' => false],
                ['name' => 'players_count', 'type' => 'int', 'nullable' => false],
                ['name' => 'is_olympic', 'type' => 'tinyint(1)', 'nullable' => false, 'default' => '0'],
                ['name' => 'origin_country', 'type' => 'varchar', 'nullable' => true],
                ['name' => 'established_year', 'type' => 'int', 'nullable' => true],
                ['name' => 'created_at', 'type' => 'timestamp', 'nullable' => true],
                ['name' => 'updated_at', 'type' => 'timestamp', 'nullable' => true],
            ]
        ];
    }

    private function getExpectedStructure($table)
    {
        switch ($table) {
            case 'animes':
                return $this->getAnimesTableDefinition();
            case 'ghibli_films':
                return $this->getGhibliFilmsTableDefinition();
            case 'sports':
                return $this->getSportsTableDefinition();
            default:
                return null;
        }
    }

    private function compareStructures($actualColumns, $expectedStructure)
    {
        if (!$expectedStructure) {
            return false;
        }

        // カラム数の確認
        if (count($actualColumns) !== count($expectedStructure['columns'])) {
            return false;
        }

        // 各カラムの確認
        foreach ($expectedStructure['columns'] as $column) {
            $columnName = $column['name'];

            // カラムが存在するか確認
            if (!isset($actualColumns[$columnName])) {
                return false;
            }

            $actual = $actualColumns[$columnName];

            // 型の確認（前方一致で確認）
            if (strpos($actual['type'], $column['type']) !== 0) {
                return false;
            }

            // NULL制約の確認
            $expectedNull = $column['nullable'] ? 'YES' : 'NO';
            if ($actual['null'] !== $expectedNull) {
                return false;
            }

            // 主キーの確認
            if (isset($column['primary']) && $column['primary']) {
                if ($actual['key'] !== 'PRI') {
                    return false;
                }
            }

            // デフォルト値の確認（あれば）
            if (isset($column['default'])) {
                if ($actual['default'] != $column['default']) {
                    return false;
                }
            }
        }

        return true;
    }
}
