CREATE OR REPLACE FUNCTION corres.ft_grupo_funcionario_ime (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Correspondencia
 FUNCION: 		corres.ft_grupo_funcionario_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'corres.tgrupo_funcionario'
 AUTOR: 		 (rac)
 FECHA:	        10-01-2012 16:15:05
 COMENTARIOS:	
***************************************************************************
 HISTORIAL DE MODIFICACIONES:

 DESCRIPCION:	
 AUTOR:			
 FECHA:		
***************************************************************************/

DECLARE

	v_nro_requerimiento    	integer;
	v_parametros           	record;
	v_id_requerimiento     	integer;
	v_resp		            varchar;
	v_nombre_funcion        text;
	v_mensaje_error         text;
	v_id_grupo_funcionario	integer;
			    
BEGIN

    v_nombre_funcion = 'corres.ft_grupo_funcionario_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'CO_FUNA_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		rac	
 	#FECHA:		10-01-2012 16:15:05
	***********************************/

	if(p_transaccion='CO_FUNA_INS')then
					
        begin
        	--Sentencia de la insercion
        	insert into corres.tgrupo_funcionario(
			id_grupo,
			estado_reg,
			id_funcionario,
			id_usuario_reg,
			fecha_reg,
			fecha_mod,
			id_usuario_mod
          	) values(
			v_parametros.id_grupo,
			'activo',
			v_parametros.id_funcionario,
			p_id_usuario,
			now(),
			null,
			null
			)RETURNING id_grupo_funcionario into v_id_grupo_funcionario;
               
			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Funcionarios Agrupados almacenado(a) con exito (id_grupo_funcionario'||v_id_grupo_funcionario||')'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_grupo_funcionario',v_id_grupo_funcionario::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************    
 	#TRANSACCION:  'CO_FUNA_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		rac	
 	#FECHA:		10-01-2012 16:15:05
	***********************************/

	elsif(p_transaccion='CO_FUNA_MOD')then

		begin
			--Sentencia de la modificacion
			update corres.tgrupo_funcionario set
			id_grupo = v_parametros.id_grupo,
			id_funcionario = v_parametros.id_funcionario,
			fecha_mod = now(),
			id_usuario_mod = p_id_usuario
			where id_grupo_funcionario=v_parametros.id_grupo_funcionario;
               
			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Funcionarios Agrupados modificado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_grupo_funcionario',v_parametros.id_grupo_funcionario::varchar);
               
            --Devuelve la respuesta
            return v_resp;
            
		end;

	/*********************************    
 	#TRANSACCION:  'CO_FUNA_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		rac	
 	#FECHA:		10-01-2012 16:15:05
	***********************************/

	elsif(p_transaccion='CO_FUNA_ELI')then

		begin
			--Sentencia de la eliminacion
			delete from corres.tgrupo_funcionario
            where id_grupo_funcionario=v_parametros.id_grupo_funcionario;
               
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Funcionarios Agrupados eliminado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_grupo_funcionario',v_parametros.id_grupo_funcionario::varchar);
              
            --Devuelve la respuesta
            return v_resp;

		end;
         
	else
     
    	raise exception 'Transaccion inexistente: %',p_transaccion;

	end if;

EXCEPTION
				
	WHEN OTHERS THEN
		v_resp='';
		v_resp = pxp.f_agrega_clave(v_resp,'mensaje',SQLERRM);
		v_resp = pxp.f_agrega_clave(v_resp,'codigo_error',SQLSTATE);
		v_resp = pxp.f_agrega_clave(v_resp,'procedimientos',v_nombre_funcion);
		raise exception '%',v_resp;
				        
END;
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
COST 100;