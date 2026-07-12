<?PHP

declare(strict_types=1);

namespace src\Data;

use src\Data\config\DBConfig;

class LoginAbuseDAO extends DBConfig
{
    protected $con = "";

    public function __construct()
    {
        global $connection;
        $this->con = $connection;
    }

    public function checkTempBannedFailedLoginIP($ipAddr, $failedLoginTable, $maxLogin24h = 20, array $columns = array())
    {
        $columns = array_merge(array(
            'id' => 'id',
            'ip' => 'ip',
            'date' => 'date',
            'type' => 'type',
            'cookieLogin' => null,
            'cookieLoginValue' => '0',
        ), $columns);

        $table = $this->quoteIdentifier($failedLoginTable);
        $idColumn = $this->quoteIdentifier($columns['id']);
        $ipColumn = $this->quoteIdentifier($columns['ip']);
        $dateColumn = $this->quoteIdentifier($columns['date']);
        $typeColumn = $this->quoteIdentifier($columns['type']);
        $cookieLoginWhere = '';
        if(isset($columns['cookieLogin']) && $columns['cookieLogin'] !== null)
        {
            $cookieLoginColumn = $this->quoteIdentifier($columns['cookieLogin']);
            $cookieLoginWhere = " AND {$cookieLoginColumn}= :cookieLogin";
        }

        $qry = "SELECT COUNT({$idColumn}) AS `total` FROM {$table} WHERE {$ipColumn}= :ip AND {$dateColumn}> :datePast AND {$dateColumn}< :dateTo{$cookieLoginWhere} AND {$typeColumn} NOT IN (4, 5) LIMIT 1";
        $prms = array(':ip' => $ipAddr, ':datePast' => date('Y-m-d H:i:s', strtotime('-72 hours')), ':dateTo' => date('Y-m-d H:i:s', strtotime('-48 hours')));
        if($cookieLoginWhere !== '')
            $prms[':cookieLogin'] = $columns['cookieLoginValue'];

        $row = $this->con->getDataSR($qry, $prms);
        if(!isset($row['total']) || (isset($row['total']) && $row['total'] < $maxLogin24h))
        {
            $prms[':datePast'] = date('Y-m-d H:i:s', strtotime('-48 hours'));
            $prms[':dateTo'] = date('Y-m-d H:i:s', strtotime('-24 hours'));
            $row = $this->con->getDataSR($qry, $prms);
        }
        if(!isset($row['total']) || (isset($row['total']) && $row['total'] < $maxLogin24h))
        {
            $qry = "SELECT COUNT({$idColumn}) AS `total` FROM {$table} WHERE {$ipColumn}= :ip AND {$dateColumn}> :datePast{$cookieLoginWhere} AND {$typeColumn} NOT IN (4, 5) LIMIT 1";
            $prms[':datePast'] = date('Y-m-d H:i:s', strtotime('-24 hours'));
            unset($prms[':dateTo']);
            $row = $this->con->getDataSR($qry, $prms);
        }

        return isset($row['total']) && $row['total'] >= $maxLogin24h;
    }

    private function quoteIdentifier($identifier)
    {
        if(!is_string($identifier) || !preg_match('/^[A-Za-z0-9_]+$/', $identifier))
            throw new \InvalidArgumentException('Invalid SQL identifier.');

        return '`' . $identifier . '`';
    }
}
