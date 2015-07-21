<?

class DoctrineUtils {

    public static function runRawSql($query, $params, $one = false){
        $stmt = Doctrine_Manager::connection()->execute($query, $params);
        if ($one) return $stmt->fetch();
        else {
            return $stmt->fetchAll();
        }
    }

    public static function generateInPlaceholders($input){
        $ret = "(";
        foreach($input as $i) $ret .= "?, ";

        $ret = trim($ret, ", ");
        $ret .= ")";
        return $ret;
    }

    public static function tabularize(DoctrineQuery $q, $options = array('show-sql' => true, 'table' => true)) {
        if ($options['show-sql']) {
            echo sprintf('SQL: %s', $q->getSqlQuery()) . "\n";
        }

        $count = $q->count();

        if ($count) {
            $results = $q->fetchArray();

            if (!$options['table']) {
                echo sprintf('found %s results', $count) . "\n";
                $yaml = sfYaml::dump($results, 4);
                $lines = explode("\n", $yaml);
                foreach ($lines as $line) {
                    echo $line . "\n";
                }
            } else {
                $headers = array();
                // calculate lengths
                foreach ($results as $result) {
                    foreach ($result as $field => $value) {
                        if (!isset($headers[$field]))
                            $headers[$field] = 0;
                        $headers[$field] = max($headers[$field], strlen($value));
                    }
                }

                // print headers
                $hdr = "|";
                $div = "+";
                foreach ($headers as $field => &$length) {
                    if ($length < strlen($field))
                        $length = strlen($field);

                    $hdr .= " " . str_pad($field, $length) . " |";
                    $div .= str_pad("", $length + 2, "-") . "+";
                }
                echo $div . "\n";
                echo $hdr . "\n";
                echo $div . "\n";

                // print results
                foreach ($results as $result) {
                    echo( "|" );
                    foreach ($result as $field => $value) {
                        echo " " . str_pad($value, $headers[$field]) . " |";
                    }
                    echo "\n";
                }
                echo $div . "\n";
                echo sprintf('(%s results)', $count) . "\n";
                echo "\n";
            }
        }
    }
}
?>