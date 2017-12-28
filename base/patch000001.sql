/***********************************I-SCP-FRH-CORRES-0-24/01/2013****************************************/

/**	Author: Freddy Rojas FRH
*	Date: 24/01/2013
*	Description: Build the menu definition and composition
*/
 
/* (1) Tables creation */


-- tcorrespondencia 
CREATE TABLE corres.tcorrespondencia (
    id_correspondencia serial NOT NULL,
    id_correspondencia_fk integer,
    id_funcionario integer,
    id_institucion integer,
    id_persona integer,
    id_uo integer,
    id_depto integer,
    id_documento integer,
    numero varchar(20) NOT NULL,
    referencia varchar(500),
    mensaje text,
    tipo varchar(20),
    version integer DEFAULT 0 NOT NULL,
    estado varchar(30) NOT NULL,
    nivel integer NOT NULL,
    id_gestion integer NOT NULL,
    fecha_documento date NOT NULL,
    id_periodo integer NOT NULL,
    id_acciones integer[],
    id_correspondencias_asociadas integer[],
    respuestas varchar(500),
    fecha_fin date,
    nivel_prioridad varchar(20) NOT NULL,
    sw_responsable varchar(2) DEFAULT 'no'::character varying NOT NULL,
    observaciones_estado text,
    origen varchar(500),
    id_clasificador integer NOT NULL,
    ruta_archivo varchar(250),
    cite varchar(250),
    CONSTRAINT tcorrespondencia__estado__chk CHECK ((((((((estado)::text = 'borrador_detalle_recibido'::text) OR ((estado)::text = 'pendiente_recibido'::text)) OR ((estado)::text = 'recibido'::text)) OR ((estado)::text = 'borrador_derivado'::text)) OR ((estado)::text = 'borrador_envio'::text)) OR ((estado)::text = 'enviado'::text))), 
    CONSTRAINT pk_tcorrespondencia__id_correspondencia PRIMARY KEY (id_correspondencia)
)
INHERITS (pxp.tbase) WITHOUT OIDS;
ALTER TABLE corres.tcorrespondencia OWNER TO postgres;

-- tarchivo 

CREATE TABLE corres.tarchivo (
    id_archivo serial NOT NULL,
    data bytea,
	CONSTRAINT pk_tcorrespondencia__id_archivo PRIMARY KEY (id_archivo)
) WITHOUT OIDS;
ALTER TABLE corres.tarchivo OWNER TO postgres;

-- tcorrespondencia_estado 

CREATE TABLE corres.tcorrespondencia_estado (
    id_correspondencia_estado serial NOT NULL,
    id_correspondencia integer,
    estado varchar(20) NOT NULL,
    estado_ant varchar(20),
    boservaciones_estado text,
	CONSTRAINT pk_tcorrespondencia__id_correspondencia_estado PRIMARY KEY (id_correspondencia_estado)
)
INHERITS (pxp.tbase) WITHOUT OIDS;
ALTER TABLE corres.tcorrespondencia_estado OWNER TO postgres;

-- tcomision 

CREATE TABLE corres.tcomision (
    id_comision serial NOT NULL,
    id_funcionario integer,
    id_correspondencia integer,
	CONSTRAINT pk_tcorrespondencia__id_comision PRIMARY KEY (id_comision)
)
INHERITS (pxp.tbase) WITHOUT OIDS;
ALTER TABLE corres.tcomision OWNER TO postgres;

-- taccion 

CREATE TABLE corres.taccion (
    id_accion serial NOT NULL,
    nombre varchar(30),
	CONSTRAINT pk_tcorrespondencia__id_accion PRIMARY KEY (id_accion)
)
INHERITS (pxp.tbase) WITHOUT OIDS;
ALTER TABLE corres.taccion OWNER TO postgres;

-- tgrupo 

CREATE TABLE corres.tgrupo (
    id_grupo serial NOT NULL,
    nombre varchar(250),
    correo varchar(100),
    obs text,
	CONSTRAINT pk_tcorrespondencia__id_grupo PRIMARY KEY (id_grupo)
)
INHERITS (pxp.tbase) WITHOUT OIDS;
ALTER TABLE corres.tgrupo OWNER TO postgres;

-- tgrupo_funcionario

CREATE TABLE corres.tgrupo_funcionario (
    id_grupo_funcionario serial NOT NULL,
    id_grupo integer,
    id_funcionario integer,
	CONSTRAINT pk_tcorrespondencia__id_grupo_funcionario PRIMARY KEY (id_grupo_funcionario)
)
INHERITS (pxp.tbase) WITHOUT OIDS;
ALTER TABLE corres.tgrupo_funcionario OWNER TO postgres;

/***********************************F-SCP-FRH-CORRES-0-24/01/2013*****************************************/




/***********************************I-SCP-RAC-CORRES-0-17/12/2015*****************************************/



--------------- SQL ---------------

ALTER TABLE corres.tcorrespondencia
  ALTER COLUMN numero TYPE VARCHAR COLLATE pg_catalog."default";



/***********************************F-SCP-RAC-CORRES-0-17/12/2015*****************************************/




/***********************************I-SCP-FFP-CORRES-0-08/03/2016*****************************************/


-- object recreation
ALTER TABLE corres.tcorrespondencia
DROP CONSTRAINT tcorrespondencia__estado__chk RESTRICT;


ALTER TABLE corres.tcorrespondencia
ADD CONSTRAINT tcorrespondencia__estado__chk CHECK (((((((((estado) :: TEXT = 'borrador_detalle_recibido' :: TEXT) OR
                                                        ((estado) :: TEXT = 'pendiente_recibido' :: TEXT)) OR
                                                       ((estado) :: TEXT = 'recibido' :: TEXT)) OR
                                                      ((estado) :: TEXT = 'recibido_derivacion' :: TEXT)) OR
                                                     ((estado) :: TEXT = 'borrador_envio' :: TEXT)) OR
                                                    ((estado) :: TEXT = 'enviado' :: TEXT)) OR
                                                    ((estado) :: TEXT = 'borrador_recepcion_externo' :: TEXT)) OR
                                                    ((estado) :: TEXT = 'pendiente_recepcion_externo' :: TEXT));



ALTER TABLE corres.tcorrespondencia ADD id_origen INTEGER NULL;


CREATE TABLE corres.tadjunto (
  id_adjunto serial NOT NULL,
  ruta_archivo VARCHAR(255),
  nombre_archivo VARCHAR(255),
  extension VARCHAR(255),
  id_correspondencia_origen INTEGER,
  CONSTRAINT pk_tcorrespondencia__id_adjunto PRIMARY KEY (id_adjunto)
)
  INHERITS (pxp.tbase) WITHOUT OIDS;



ALTER TABLE corres.tcorrespondencia ADD sw_archivado VARCHAR(2) DEFAULT 'no' NULL;


ALTER TABLE corres.tcorrespondencia ADD estado_fisico VARCHAR(255) DEFAULT 'pendiente' NOT NULL;


CREATE TABLE corres.tdocumento_fisico (
  id_documento_fisico serial NOT NULL,
  id_correspondencia INTEGER,
  id_correspondencia_padre INTEGER,
  estado VARCHAR(255),
  CONSTRAINT pk_tdocumentofisico__id_documento_fisico PRIMARY KEY (id_documento_fisico)
)INHERITS (pxp.tbase) WITHOUT OIDS;





ALTER TABLE corres.tcorrespondencia ADD vista VARCHAR(255) NULL;

/***********************************F-SCP-FFP-CORRES-0-08/03/2016*****************************************/


/***********************************I-SCP-RAC-CORRES-0-07/02/2017*****************************************/


ALTER TABLE corres.tcorrespondencia_estado
  RENAME COLUMN boservaciones_estado TO observaciones_estado;
  
  --------------- SQL ---------------

ALTER TABLE corres.tcorrespondencia
  ADD COLUMN fecha_ult_derivado TIMESTAMP WITHOUT TIME ZONE;
  
  
  
/***********************************F-SCP-RAC-CORRES-0-07/02/2017*****************************************/
  
/***********************************I-DEP-FPC-CORRES-0-21/11/2017*****************************************/
  ALTER TABLE corres.tcorrespondencia
  ADD COLUMN nro_paginas INTEGER;

  COMMENT ON COLUMN corres.tcorrespondencia.nro_paginas
  IS 'Este campo sirve para indicar el numero de paginas de un documento recibido';
    
 /***********************************F-DEP-FPC-CORRES-0-21/11/2017*****************************************/
 /***********************************I-DEP-FPC-CORRES-0-27/11/2017*****************************************/
  ALTER TABLE corres.tcorrespondencia
  ADD COLUMN otros_adjuntos VARCHAR(200);

  COMMENT ON COLUMN corres.tcorrespondencia.otros_adjuntos
  IS 'Descripcion de adjuntos que no pueden escanear';
 
  ALTER TABLE corres.tcorrespondencia
  ADD COLUMN sw_fisico VARCHAR(2) DEFAULT 'si'::character varying NOT NULL;

  COMMENT ON COLUMN corres.tcorrespondencia.sw_fisico
  IS 'Indica quien tiene el fisico';
/***********************************F-DEP-FPC-CORRES-0-27/11/2017*****************************************/

/***********************************I-SCP-FPC-CORRES-0-27/12/2017*****************************************/
ALTER TABLE corres.tcorrespondencia
  ALTER COLUMN otros_adjuntos TYPE VARCHAR(2000) COLLATE pg_catalog."default";


ALTER TABLE corres.tcorrespondencia
  DROP CONSTRAINT tcorrespondencia__estado__chk RESTRICT;

ALTER TABLE corres.tcorrespondencia
  ADD CONSTRAINT tcorrespondencia__estado__chk CHECK (((estado)::text = 'borrador_detalle_recibido'::text) OR ((estado)::text = 'pendiente_recibido'::text) OR ((estado)::text = 'recibido'::text) OR ((estado)::text = 'recibido_derivacion'::text) OR ((estado)::text = 'borrador_envio'::text) OR ((estado)::text = 'enviado'::text) OR ((estado)::text = 'borrador_recepcion_externo'::text) OR ((estado)::text = 'pendiente_recepcion_externo'::text)OR ((estado)::text = 'anulado'::text));
  /***********************************F-SCP-FPC-CORRES-0-27/12/2017*****************************************/