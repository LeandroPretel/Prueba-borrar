<?php

/** Convention foreign keys: plugin for Adminer
 * Links for foreign keys by convention userId => User.id.
 * @author Sergio García
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2 (one or other)
 */
class ConventionForeignKeys
{
    function foreignKeys($table)
    {
        global $on_actions;
        $return = array();
        foreach (get_rows("SELECT conname, condeferrable::int AS deferrable, pg_get_constraintdef(oid) AS definition
FROM pg_constraint
WHERE conrelid = (SELECT pc.oid FROM pg_class AS pc INNER JOIN pg_namespace AS pn ON (pn.oid = pc.relnamespace) WHERE pc.relname = " . q($table) . " AND pn.nspname = current_schema())
AND contype = 'f'::char
ORDER BY conkey, conname") as $row) {
            if (preg_match('~FOREIGN KEY\s*\((.+)\)\s*REFERENCES (.+)\((.+)\)(.*)$~iA', $row['definition'], $match)) {
                $row['source'] = array_map('trim', explode(',', str_replace('"', "", $match[1])));
                if (preg_match('~^(("([^"]|"")+"|[^"]+)\.)?"?("([^"]|"")+"|[^"]+)$~', $match[2], $match2)) {
                    $row['ns'] = str_replace('""', '"', preg_replace('~^"(.+)"$~', '\1', $match2[2]));
                    $row['table'] = str_replace('""', '"', preg_replace('~^"(.+)"$~', '\1', $match2[4]));
                }
                $row['target'] = array_map('trim', explode(',', $match[3]));
                $row['on_delete'] = (preg_match("~ON DELETE ($on_actions)~", $match[4], $match2) ? $match2[1] : 'NO ACTION');
                $row['on_update'] = (preg_match("~ON UPDATE ($on_actions)~", $match[4], $match2) ? $match2[1] : 'NO ACTION');
                $return[$row['conname']] = $row;
            }
        }
        return $return;
    }
}

?>
