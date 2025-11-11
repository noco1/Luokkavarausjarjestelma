Tämä on Moodle ohjeen mukaan tehty:  Tehtävä: Luokkien listaaminen (PHP + MySQL)

Omat muistiin panot:

db.php:

Kehitysvaiheessa voi tulostaa virheen, tuotannossa ei.

catch (\PDOException $e)
{
    die('DB connection failed: ' . $e->getMessage());
}

register_process:

Kehitysvaiheessa virheen tulostus voi auttaa tuotannossa lokita

catch (PDOException $e)
{
    die('Tietokantavirhe: ' . $e->getMessage());

}
