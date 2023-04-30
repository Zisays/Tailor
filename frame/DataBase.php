<?php

namespace Frame;

use PDO;
use PDOStatement;

class DataBase
{
    protected PDO $pdo;
    protected PDOStatement $pdoStmt;
    protected string $sql = '';
    protected array $whereArray = [];
    protected array $updateArray = [];
    private string $action = '';
    private array $insertArray = [];
    private array $insertParameter = ['1' => '', '2' => '', '3' => ''];
    private string $updateParameter = '';

    /**
     * 实例化
     * @param $dsn
     * @param $username
     * @param $password
     * @param $driver_options
     */
    public function __construct($dsn, $username, $password, $driver_options)
    {
        try {
            $this->pdo = new \PDO($dsn, $username, $password, $driver_options);
            return $this->pdo;
        } catch (\PDOException $e) {
            $this->error($e);
        }
        return false;
    }

    /**
     * 功能：启动一个事务
     * 说明：关闭自动提交模式。自动提交模式被关闭的同时，通过 PDO 对象实例对数据库做出的更改直到调用 commit() 结束事务才被提交。
     * 返回值：成功时返回 true， 或者在失败时返回 false。
     */
    public function begin(): bool
    {
        return $this->pdo->beginTransaction();
    }

    /**
     * 功能：检查是否在一个事务内
     * 说明：检查驱动内的一个事务当前是否处于激活。此方法仅对支持事务的数据库驱动起作用。
     * 返回值：如果当前事务处于激活，则返回 true ，否则返回 false 。
     * @return bool
     */
    public function inTrans(): bool
    {
        return $this->pdo->inTransaction();
    }

    /**
     * 功能：提交一个事务
     * 说明：提交一个事务，数据库连接返回到自动提交模式直到下次调用 begin() 开始一个新的事务为止。
     * 返回值：成功时返回 true， 或者在失败时返回 false。
     */
    public function commit(): bool
    {
        return $this->pdo->commit();
    }

    /**
     * 功能：回滚一个事务
     * 说明：回滚由 begin() 发起的当前事务。如果没有事务激活，将抛出一个 PDOException 异常。
     * 如果数据库被设置成自动提交模式，此函数（方法）在回滚事务之后将恢复自动提交模式。
     */
    public function rollback(): bool
    {
        return $this->pdo->rollBack();
    }

    /**
     * 功能：执行 SQL 语句，以 PDOStatement 对象形式返回结果集
     * PDO::query() 在单次函数调用内执行 SQL 语句，以 PDOStatement 对象形式返回结果集（如果有数据的话）。
     * 如果反复调用同一个查询，用 PDO::prepare() 准备 PDOStatement 对象，并用 PDOStatement::execute() 执行语句，将具有更好的性能。
     * 如果没有完整获取结果集内的数据，就调用下一个 PDO::query()，将可能调用失败。
     * 应当在执行下一个 PDO::query() 前，先用 PDOStatement::closeCursor() 释放数据库PDOStatement 关联的资源。
     * @param string $statement
     * @return bool|array
     */
    public function query(string $statement): bool|array
    {
        try {
            $queryArray = [];
            $stm = $this->pdo->query($statement);
            foreach ($stm->fetchAll() as $k => $v) {
                foreach ($v as $key => $value) {
                    if (!is_numeric($key)) {
                        $queryArray[$k][$key] = $value;
                    }
                }
            }
            return $queryArray;
        } catch (\PDOException $e) {
            $this->error($e);
        }
        return false;
    }

    /**
     * 功能：执行一条SQL语句，并返回受影响的行数
     * 说明：
     * exec() 在一个单独的函数调用中执行一条 SQL 语句，返回受此语句影响的行数。
     * exec() 不会从一条 SELECT 语句中返回结果。
     * 对于在程序中只需要发出一次的 SELECT 语句，可以考虑使用 query()。
     * 对于需要发出多次的语句，可用 prepare() 来准备一个 PDOStatement 对象并用 PDOStatement::execute() 发出语句。
     * 返回值：返回受修改或删除SQL语句影响的行数。如果没有受影响的行，则返回 0。
     * @param string $sql
     * @return bool|int
     */
    public function exec(string $sql): bool|int
    {
        try {
            $this->pdo->exec($sql);
            return $this->rowCount();
        } catch (\PDOException $e) {
            $this->error($e);
        }
        return false;
    }

    /**
     * 功能：获取 PDO 当前连接属性的值
     * 说明：此函数（方法）返回一个数据库连接的属性值。 取回 PDOStatement 属性
     * 注意有些数据库/驱动可能不支持所有的数据库连接属性。
     * 返回值：成功调用则返回请求的 PDO 属性值。不成功则返回 null。
     * @param int $attribute
     * @return mixed
     */
    public function getAttr(int $attribute): mixed
    {
        try {
            return $this->pdo->getAttribute($attribute);
        } catch (\PDOException $e) {
            $this->error($e);
        }
        return false;
    }

    /**
     * 功能：设置 PDO 当前连接属性的值
     * 说明：设置数据库句柄属性。下面列出了一些可用的通用属性；有些驱动可能使用另外的特定属性。
     * 返回值：成功时返回 true， 或者在失败时返回 false。
     * @param int $attribute
     * @param mixed $value
     * @return bool
     */
    public function setAttr(int $attribute, mixed $value): bool
    {
        try {
            return $this->pdo->setAttribute($attribute, $value);
        } catch (\PDOException $e) {
            $this->error($e);
        }
        return false;
    }

    /**
     * 功能：返回当前可用的 PDO 驱动
     * 说明：此函数（方法）返回所有当前可用在 __construct() 的参数 DSN 中的 PDO 驱动。
     * 返回值：返回一个 包含可用 PDO 驱动名字的数组。如果没有可用的驱动，则返回一个空数组。
     * @return bool|array
     */
    public function getDrivers(): bool|array
    {
        try {
            return $this->pdo->getAvailableDrivers();
        } catch (\PDOException $e) {
            $this->error($e);
        }
        return false;
    }

    /**
     * 功能：返回受上一个 SQL 语句影响的行数
     * @return int
     */
    public function rowCount(): int
    {
        return $this->pdoStmt->rowCount();
    }

    /**
     * 功能：返回结果集中的列数
     * @return int
     */
    public function columnCount(): int
    {
        return $this->pdoStmt->columnCount();
    }

    /**
     * 功能：返回最后插入行的ID或序列值
     * 说明：返回最后插入行的ID，或者是一个序列对象最后的值，取决于底层的驱动。
     * 返回值：
     * 如果没有为参数 name 指定序列名称，PDO::lastInsertId() 则返回一个表示最后插入数据库那一行的行ID的字符串。
     * 如果为参数 name 指定了序列名称，PDO::lastInsertId() 则返回一个表示从指定序列对象取回最后的值的字符串。
     * 如果当前 PDO 驱动不支持此功能，则 PDO::lastInsertId() 触发一个 IM001 SQLSTATE 。
     * @param string|null $name
     * @return string
     */
    public function lastInsertId(string $name = null): string
    {
        if (!empty($name)) {
            return $this->pdo->lastInsertId($name);
        } else {
            return $this->pdo->lastInsertId();
        }
    }

    /**
     * 功能：打印一条 SQL 预处理命令
     */
    public function sql(): void
    {
        exit($this->pdoStmt->debugDumpParams());
    }

    /**
     * 功能：准备要执行的语句，并返回语句对象
     * 说明：为 PDOStatement::execute() 方法准备待执行的 SQL 语句。
     * @param string $statement 必须是对目标数据库服务器有效的 SQL 语句模板
     * @param array $driver_options 数组包含一个或多个 key=>value 键值对，为返回的 PDOStatement 对象设置属性。
     * 常见用法是：设置 PDO::ATTR_CURSOR 为 PDO::CURSOR_SCROLL，将得到可滚动的光标。某些驱动有驱动级的选项，在 prepare 时就设置。
     */
    public function prepare(string $statement, array $driver_options = []): bool|PDOStatement
    {
        try {
            return $this->pdo->prepare($statement, $driver_options);
        } catch (\PDOException $e) {
            $this->error($e);
        }
        return false;
    }

    /**
     * 功能：为 SQL 查询里的字符串添加引号
     * @param string $string
     * @param int $parameter_type
     * @return bool|string
     */
    public function quote(string $string, int $parameter_type = PDO::PARAM_STR): bool|string
    {
        return $this->pdo->quote($string, $parameter_type);
    }

    /**
     * 功能：输出错误
     * @param $e
     * @return void
     */
    protected function error($e): void
    {

        $getMessage = $e->getMessage();
        if (ENV::get('DEBUG')) {
            $getCode = $e->getCode();
            $getFile = $e->getFile();
            $getLine = $e->getLine();
            $getTraceAsString = $e->getTraceAsString();
            $getPrevious = $e->getPrevious();
            if (!empty($this->sql)) {
                $prepareSql = $this->sql;
            } else {
                $prepareSql = '';
            }
            if (!empty($this->updateArray)) {
                $prepareUpdate = $this->updateArray;
            } else {
                $prepareUpdate = '';
            }
            if (!empty($this->whereArray)) {
                $prepareWhere = $this->whereArray;
            } else {
                $prepareWhere = '';
            }
            $getTraceArray = $e->getTrace();
            if (count($getTraceArray) > 0) {
                $getTrace = $getTraceArray;
            } else {
                $getTrace = '';
            }
            $error = [
                '错误编号' => $getCode,
                '错误信息' => $getMessage,
                '错误文件' => $getFile,
                '错误行号' => $getLine,
                '预处理 SQL 语句' => $prepareSql,
                '预处理 SQL 参数 ( Update )' => $prepareUpdate,
                '预处理 SQL 参数 ( Where )' => $prepareWhere,
                '堆栈跟踪 ( 数组 )' => $getTrace,
                '堆栈跟踪 ( 字符串 )' => $getTraceAsString,
                '上一个错误' => $getPrevious,
            ];
        } else {
            $error = $getMessage;
        }
        Api::error($error);
    }

    public function select(string $column = '*'): static
    {
        $this->action = 'select';
        $this->sql = ' select ' . $column;
        return $this;
    }

    public function count($table)
    {
        $count = $this->select('count(*) as count')->from($table)->run();
        return $count[0]['count'];
    }

    public function from(string $table): static
    {
        $this->sql .= ' from ' . $table;
        return $this;
    }

    public function where(string $column, mixed $arithmetic = '=', mixed $value = ''): static
    {
        if ($arithmetic == 'in') {
            $this->sql .= ' where ' . $column . ' in ( ';
            $splicing = '';
            foreach ($value as $v) {
                $splicing .= '?,';
                $this->whereArray[count($this->whereArray) + 1] = $v;
            }
            $this->sql .= substr($splicing, 0, -1) . ')';
        } else {
            $this->whereAdd('where', $column, $arithmetic, $value);
        }
        return $this;
    }

    public function and(string $column, mixed $arithmetic = '=', mixed $value = ''): static
    {
        $this->whereAdd('and', $column, $arithmetic, $value);
        return $this;
    }

    public function or(string $column, mixed $arithmetic = '=', mixed $value = ''): static
    {
        $this->whereAdd('or', $column, $arithmetic, $value);
        return $this;
    }

    private function whereAdd(string $action, string $column, mixed $arithmetic, mixed $value): void
    {
        if (!empty($value)) {
            $this->value($action, $column, $arithmetic, $value);
        } else {
            $this->arithmetic($action, $column, $arithmetic);
        }
    }

    private function arithmetic(string $action, string $column, mixed $arithmetic): void
    {
        $this->sql .= ' ' . $action . ' ' . $column . '=? ';
        $this->whereArray[count($this->whereArray) + 1] = $arithmetic;
    }

    private function value(string $action, string $column, mixed $arithmetic, mixed $value): void
    {
        $this->sql .= ' ' . $action . ' ' . $column . $arithmetic . '? ';;
        $this->whereArray[count($this->whereArray) + 1] = $value;
    }

    public function groupBy(string $condition): static
    {
        $this->sql .= ' group by ' . $condition;
        return $this;
    }

    public function having(string $column, string $arithmetic = '=', string $value = ''): static
    {
        $this->sql .= ' having ' . $column . $arithmetic . '?';
        $this->whereArray[count($this->whereArray) + 1] = array($arithmetic, $value);
        return $this;
    }

    public function orderBy(string $column, string $sort = 'asc'): static
    {
        $this->sql .= ' order by ' . $column . ' ' . $sort;
        return $this;
    }

    public function limit(string $start, string $end = ''): static
    {
        $this->sql .= ' limit ' . $start;
        if (!empty($end)) {
            $this->sql .= ',' . $end;
        }
        return $this;
    }

    public function insertInto(string $table): static
    {
        $this->action = 'insert';
        $this->sql = 'insert into ' . $table;
        return $this;
    }

    public function values(array $insertDataArray): static
    {
        $i = 1;
        foreach ($insertDataArray as $k => $v) {
            $this->insertParameter['1'] .= $k . ',';
            $this->insertParameter['2'] .= '?,';
            $this->insertParameter['3'] .= $v . ',';
            $this->insertArray[$i] = $v;
            $i++;
        }
        $i = 0;
        return $this;
    }

    private function insertSplicing(string $sql): string
    {
        $column = substr($this->insertParameter['1'], 0, -1);
        $preValue = substr($this->insertParameter['2'], 0, -1);
        $value = substr($this->insertParameter['3'], 0, -1);
        $this->sql = $sql . '(' . $column . ') values(' . $value . ')';
        return $sql . '(' . $column . ') values(' . $preValue . ')';
    }

    public function update(string $table): static
    {
        $this->action = 'update';
        $this->sql = 'update ' . $table . ' set ';
        return $this;
    }

    public function set(array $DataArray): static
    {
        $s = 1;
        foreach ($DataArray as $k => $v) {
            $this->updateParameter .= $k . '=?,';
            $this->updateArray[$s] = $v;
            $s++;
        }
        $this->sql .= substr($this->updateParameter, 0, -1);
        return $this;
    }

    public function deleteFrom(string $table): static
    {
        $this->action = 'delete';
        $this->sql = 'delete from' . $table;
        return $this;
    }

    public function run(): bool|array
    {
        return match ($this->action) {
            'select' => $this->runSelect(),
            'insert' => $this->runInsert(),
            'update' => $this->runUpdate(),
            'delete' => $this->runDelete()
        };
    }

    private function runSelect(): bool|array
    {
        try {
            $this->extracted();
            $this->pdoStmt->execute();
            $rSelect = $this->pdoStmt->fetchAll(\PDO::FETCH_ASSOC);
            $this->whereArray = array();
            return $rSelect;
        } catch (\PDOException $e) {
            $this->error($e);
        }
        return false;
    }

    private function runInsert(): bool
    {
        try {
            $sql = $this->insertSplicing($this->sql);
            $this->pdoStmt = $this->prepare($sql, [\PDO::ATTR_CURSOR => \PDO::CURSOR_SCROLL]);
            if (!empty($this->insertArray)) {
                $count = count($this->insertArray);
                for ($i = 1; $i <= $count; $i++) {
                    $this->pdoStmt->bindValue($i, $this->insertArray[$i]);
                }
            }
            $this->pdoStmt->execute();
            $this->insertArray = array();
            return true;
        } catch (\PDOException $e) {
            $this->error($e);
        }
        return false;
    }

    private function runUpdate(): bool
    {
        try {
            $this->pdoStmt = $this->prepare($this->sql, [\PDO::ATTR_CURSOR => \PDO::CURSOR_SCROLL]);
            $updateWhereArray = array_merge($this->updateArray, $this->whereArray);
            $this->updateArray = array_combine(range(1, count($updateWhereArray)), array_values($updateWhereArray));
            if (!empty($this->updateArray)) {
                $count = count($this->updateArray);
                for ($i = 1; $i <= $count; $i++) {
                    $this->pdoStmt->bindValue($i, $this->updateArray[$i]);
                }
            }
            $this->pdoStmt->execute();
            $this->updateArray = array();
            $this->whereArray = array();
            $this->updateParameter = '';
            return true;
        } catch (\PDOException $e) {
            $this->error($e);
        }
        return false;
    }

    private function runDelete(): bool
    {
        try {
            $this->extracted();
            $this->pdoStmt->execute();
            $this->whereArray = array();
            return true;
        } catch (\PDOException $e) {
            $this->error($e);
        }
        return false;
    }

    private function extracted(): void
    {
        $this->pdoStmt = $this->prepare($this->sql, [\PDO::ATTR_CURSOR => \PDO::CURSOR_SCROLL]);
        if (!empty($this->whereArray)) {
            $count = count($this->whereArray);
            for ($i = 1; $i <= $count; $i++) {
                $this->pdoStmt->bindValue($i, $this->whereArray[$i]);
            }
        }
    }

}