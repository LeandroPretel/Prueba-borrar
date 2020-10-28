CREATE OR REPLACE FUNCTION fn_triggerall(DoEnable boolean) RETURNS integer AS
$BODY$
DECLARE
mytables RECORD;
BEGIN
  FOR mytables IN SELECT relname FROM pg_class WHERE relhastriggers = TRUE AND NOT relname LIKE 'pg_%'
  LOOP
    IF DoEnable THEN
      EXECUTE 'ALTER TABLE "' || mytables.relname || '" ENABLE TRIGGER ALL';
    ELSE
      EXECUTE 'ALTER TABLE "' || mytables.relname || '" DISABLE TRIGGER ALL';
    END IF;
  END LOOP;
  RETURN 1;
END;
$BODY$
LANGUAGE 'plpgsql' VOLATILE;
ALTER FUNCTION fn_triggerall(DoEnable boolean) OWNER TO vpedrosa;
COMMENT ON FUNCTION fn_triggerall(DoEnable boolean) IS 'Enable/disable all the triggers in database';
SELECT fn_triggerall(false);