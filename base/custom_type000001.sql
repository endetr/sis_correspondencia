/***********************************I-TYP-FFP-CORRES-1-22/04/2016****************************************/

CREATE TYPE corres.json_adjuntos_ins AS (
  nombre_archivo VARCHAR(255),
  extension VARCHAR(255),
  ruta_archivo VARCHAR(255),
  id_correspondencia_origen INTEGER
);

/***********************************F-TYP-FPP-CORRES-1-22/04/2016****************************************/


/***********************************I-TYP-MANU-CORRES-1-05/03/2020****************************************/

ALTER TYPE corres.json_adjuntos_ins
  ADD ATTRIBUTE id_correspondencia INTEGER;

/***********************************F-TYP-MANU-CORRES-1-05/03/2020****************************************/