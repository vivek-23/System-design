<?php

class SomeClass
{
    public function up()
    {
        $this->runQuery('
            ALTER table some_table
                ADD COLUMN some_column boolean;
        ');

        $serialNo = 0;
      
        do {
            $serialNo += 1;
            $rowsUpdated = $this->runQuery('
                    UPDATE some_table
                    SET some_column = true
                    WHERE id IN (
                        SELECT
                            id
                        FROM
                            some_table
                        WHERE
                            some_column IS NULL
                        LIMIT 5000
                    )
                ')
                ->execute();

            echo "#{$serialNo} - {$rowsUpdated} rows updated\n";
        } while ($rowsUpdated > 0);

        $this->runQuery('
            ALTER table some_table
                ALTER COLUMN some_column SET DEFAULT true;
        ');

        $this->runQuery('
            ALTER table some_table
                ALTER COLUMN some_column SET NOT NULL;
        ');
    }

    public function down()
    {
       $this->runQuery('
            ALTER table some_table
                DROP COLUMN some_column;
        ');
    }
}
