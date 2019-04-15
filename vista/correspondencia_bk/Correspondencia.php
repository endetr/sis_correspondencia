<?php
/**
 *@package pXP
 *@file gen-Correspondencia.php
 *@author  (rac)
 *@date 13-12-2011 16:13:21
 *@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
 */
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.Correspondencia=Ext.extend(Phx.gridInterfaz,{
	bsave:false,
	fwidth:'90%',
	fheight:'90%',

	constructor:function(config){
		this.maestro=config.maestro;
    	//llama al constructor de la clase padre
		Phx.vista.Correspondencia.superclass.constructor.call(this,config);
		 
		this.addButton('aSubirCorrespondencia',{text:'Subir Documento',iconCls:'bupload',disabled:true,handler:this.SubirCorrespondencia,tooltip: '<b>Subir archivo</b><br/>Permite actualizar el documento escaneado'});
		this.addButton('corregir',{text:'Corregir',iconCls:'bundo',disabled:true,handler:this.onButtonCorregir,tooltip: '<b>Corregir</b><br/>Si todos los envios se detinadtarios se encuentras pendientes de lectura puede solicitar la correcion'});
		this.addButton('mandar',{text:'Derivar',iconCls:'badelante',disabled:true,handler:this.onButtonMandar,tooltip: '<b>Derivar</b><br/>Despues de scanear y seleccionar los destinatarios puede derivar la correspondencia'});
		this.addButton('verCorrespondencia',{text:'Ver Documento',iconCls: 'bsee',disabled:true,handler:this.verCorrespondencia,tooltip: '<b>Ver archivo</b><br/>Permite visualizar el documento escaneado'});
		
		this.init();
		this.store.baseParams={'interface':'emitida'};
		this.load({params:{start:0, limit:50}})
		this.iniciarEventos()
	},
	
	 SubirCorrespondencia:function()
		{					
			var rec=this.sm.getSelected();
			
						
			Phx.CP.loadWindows('../../../sis_correspondencia/vista/correspondencia/subirCorrespondencia.php',
			'Subir Correspondencia',
			{
				modal:true,
				width:500,
				height:250
		    },rec.data,this.idContenedor,'subirCorrespondencia')
	 },
		
	
	  south:{
	  url:'../../../sis_correspondencia/vista/correspondencia_detalle/CorrespondenciaDetalle.php',cls:'CorrespondenciaDetalle',
	  title:'Detalle de Correspondencia', height:'50%'
	   },	
	Atributos:[
		{
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_correspondencia'
			},
			type:'Field',
			form:true 
		},
		{
			config:{
				name: 'version',
				fieldLabel: 'Digital',
				gwidth: 60,
			    renderer:function (value, p, record){
					var icono='wrong';
					if(record.data.version>0){
						icono='good';
					}
					return "<div style='text-align:center'><img src = '../../../lib/imagenes/icono_dibu/dibu_"+icono+".png' align='center' width='32' height='32'/></div>"
				 }
			},
			type:'Field',
			egrid:true,
			filters:{pfiltro:'cor.version',type:'numeric'},
			id_grupo:0,
			grid:true,
			form:false
		},
		
		{
			config:{
				name: 'numero',
				fieldLabel: 'Numero',
				gwidth: 120				
			},
			type:'TextField',
			filters:{pfiltro:'cor.numero',type:'string'},
			id_grupo:0,
			grid:true,
			form:false
		},
		
		{
			config:{
				name: 'estado',
				fieldLabel: 'estado',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:4
			},
			type:'TextField',
			filters:{pfiltro:'cor.estado',type:'numeric'},
			id_grupo:0,
			grid:true,
			form:false
		},

		{
			config:{
				name: 'id_archivo',
				fieldLabel: 'Dig.',
				gwidth: 30,
				maxLength:4
			},
			type:'TextField',
			//filters:{pfiltro:'cor.id_archivo',type:'numeric'},
			id_grupo:0,
			grid:false,
			form:false
		},
		{
   			config:{
   				name:'id_depto',
	   				origen:'DEPTO',
	   				allowBlank:false,
	   				fieldLabel: 'Depto',
	   				gdisplayField:'desc_depto',//dibuja el campo extra de la consulta al hacer un inner join con orra tabla
	   				width:250,
   			        gwidth:200,
	   				baseParams:{estado:'activo',codigo_subsistema:'CORRES'},//parametros adicionales que se le pasan al store
	      			renderer:function (value, p, record){return String.format('{0}', record.data['desc_depto']);}
   			},
   			//type:'TrigguerCombo',
   			type:'ComboRec',
   			id_grupo:0,
   			filters:{pfiltro:'depto.nombre',type:'string'},
   		    grid:false,
   			form:true
       	},
		{
			config:{
				name: 'tipo',
				fieldLabel: 'Tipo Documento',
				typeAhead: true,
				allowBlank:false,
	    		triggerAction: 'all',
	    		emptyText:'Seleccione Opcion...',
	    		selectOnFocus:true,
	    		width:250,
				mode:'local',
			
	   			store:new Ext.data.ArrayStore({
		        	fields: ['ID', 'valor'],
		        	data :	[['interna','Interna'],	
		        			['saliente','Saliente']]
		        				
		    		}),
		    		valueField:'ID',
					displayField:'valor'
					
			},
			type:'ComboBox',
			valorInicial:'interna',
			filters:{pfiltro:'cor.tipo',type:'string'},
			id_grupo:0,
			grid:true,
			form:true
		},

		{
   			config:{
   				name:'id_documento',
   				fieldLabel:'Documento',
   				allowBlank:false,
   				emptyText:'Documento...',
   				store: new Ext.data.JsonStore({
                    url: '../../sis_parametros/control/Documento/listarDocumento',
					id: 'id_documento',
					root: 'datos',
					sortInfo:{
						field: 'codigo',
						direction: 'ASC'
					},
					totalProperty: 'total',
					fields: ['id_documento','codigo','descripcion'],
					// turn on remote sorting
					remoteSort: true,
					baseParams:{par_filtro:'DOCUME.codigo#DOCUME.descripcion'}
				}),
   				valueField: 'id_documento',
   				displayField: 'descripcion',
   				gdisplayField:'desc_documento',//mapea al store del grid
   				tpl:'<tpl for="."><div class="x-combo-list-item"><p>({codigo}) {descripcion}</p> </div></tpl>',
   				hiddenName: 'id_documento',
   				forceSelection:true,
   				typeAhead: true,
       			triggerAction: 'all',
       			lazyRender:true,
   				mode:'remote',
   				pageSize:10,
   				queryDelay:1000,
   				width:250,
   				gwidth:150,
   				minChars:2,
   				renderer:function (value, p, record){return String.format('{0}', record.data['desc_documento']);}
   			},
   			type:'ComboBox',
   			id_grupo:0,
   			filters:{	
   				        pfiltro:'doc.descripcion',
   						type:'string'
   					},
   		   
   			grid:true,
   			form:true
   	},
   	 {
			config:{
				name: 'fecha_documento',
				fieldLabel: 'Fecha Documento',
				disabled:true,
				allowBlank: false,
				format:'d-m-Y',
				width: 100,
				gwidth: 100,
				renderer:function (value,p,record){return value?value.dateFormat('d/m/Y'):''}
			},
			type:'DateField',
			valorInicial:new Date(),
			filters:{pfiltro:'cor.fecha_documento',type:'date'},
			id_grupo:2,
			grid:true,
			form:true
		},

        {
   			config:{
       		    name:'id_funcionario',
   				origen:'FUNCIONARIOCAR',
   				fieldLabel:'Funcionario Remitente',
   				gdisplayField:'desc_funcionario',//mapea al store del grid
   			    gwidth:200,
	   			renderer:function (value, p, record){return String.format('{0}', record.data['desc_funcionario']);}
       	     },
   			type:'ComboRec',
   			id_grupo:1,
   			filters:{	
		        pfiltro:'desc_funcionario1',
				type:'string'
			},
   		   
   			grid:true,
   			form:true
   	      },
      	   {
   			config:{
       		    name:'id_uo',
          		 baseParams:{correspondencia:'si'},
   				origen:'UO',
   				fieldLabel:'UO Remitente',
   				gdisplayField:'desc_uo',//mapea al store del grid
   			    gwidth:200,
   			     renderer:function (value, p, record){return String.format('{0}', record.data['desc_uo']);}
       	     },
   			type:'ComboRec',
   			id_grupo:1,
   			filters:{	
		        pfiltro:'desc_uo',
				type:'string'
			},
   		   grid:true,
   			form:true
   	      }
   	      
		,


    	{
   			config:{
       		    name:'id_persona_destino',
   				origen:'PERSONA',
      			 tinit:true,
   				fieldLabel:'Persona',
   				gdisplayField:'desc_person',//mapea al store del grid
   			    gwidth:200,
	   			 renderer:function (value, p, record){return String.format('{0}', record.data['desc_person']);}
       	     },
   			type:'ComboRec',
   			id_grupo:3,
   			filters:{	
		        pfiltro:'PERSON.nombre_completo1',
				type:'string'
			},
   		   
   			grid:false,
   			form:true
   	      },{
    	   		config:{
				name:'id_institucion_destino',
				allowBlank: true,
				fieldLabel: 'Institucion',
				anchor: '90%',
				tinit:true,
				origen:'INSTITUCION',
				gdisplayField:'nombre',
			    gwidth:200,	
			   	renderer:function (value, p, record){return String.format('{0}', record.data['nombre']);}
			
			  },
			type:'ComboRec',
			id_grupo:3,
			filters:{pfiltro:'nombre',type:'string'},
			grid:false,
			form:true
	},

	
		
    	{
   			config:{
   				name:'id_funcionarios',
   				fieldLabel:'Funcionario(s). Destino',
   				allowBlank:true,
   				emptyText:'Funcionarios...',
   				store: new Ext.data.JsonStore({  
					url: '../../sis_organigrama/control/Funcionario/listarFuncionarioCargo',
					id: 'id_funcionario',
					root: 'datos',
					sortInfo:{
						field: 'desc_funcionario1', 
						direction: 'ASC'
					},
					totalProperty: 'total',
					fields: ['id_funcionario','id_uo','codigo','nombre_cargo','desc_funcionario1','email_empresa'],
					// turn on remote sorting
					remoteSort: true,
					baseParams: {par_filtro:'desc_funcionario1#email_empresa#codigo#nombre_cargo',estado_reg_asi:'activo'}
					
				}),
				tpl:'<tpl for="."><div class="x-combo-list-item" ><div class="awesomecombo-item {checked}">{codigo}-{desc_funcionario1}</div><p style="padding-left: 20px;">{nombre_cargo}</p><p style="padding-left: 20px;">{email_empresa}</p> </div></tpl>',
			    valueField: 'id_funcionario',
   				displayField: 'desc_funcionario1',
   				hiddenName: 'id_funcionarios',
   				typeAhead: true,
       			triggerAction: 'all',
       			lazyRender:true,
   				mode:'remote',
   				pageSize:10,
   				queryDelay:1000,
   				width:250,
   				minChars:2,
       			enableMultiSelect:true
   			},
   			type:'AwesomeCombo',
   			id_grupo:3,
   			grid:false,
   			form:true
   	    },
      	
		
		{
			config:{
				name: 'referencia',
				fieldLabel: 'referencia',
				allowBlank: true,
				width: 300,
				growMin:100,
				grow : true,
				gwidth: 100,
				maxLength:500
			},
			type:'TextArea',
			filters:{pfiltro:'cor.referencia',type:'string'},
			id_grupo:2,
			grid:true,
			form:true
		},
			
		{
			config:{
				name: 'mensaje',
				fieldLabel: 'mensaje',
				allowBlank: true,
				width: 300,
				growMin:100,
				grow : true,
				gwidth: 100
			},
			type:'TextArea',
			filters:{pfiltro:'cor.mensaje',type:'string'},
			id_grupo:2,
			grid:true,
			form:true
		},
		{
   			config:{
   				name:'id_acciones',
   				fieldLabel:'Acciones',
   				allowBlank:false,
   				emptyText:'Acciones...',
   				store: new Ext.data.JsonStore({
                    url: '../../sis_correspondencia/control/Accion/listarAccion',
					id: 'id_accion',
					root: 'datos',
					sortInfo:{
						field: 'nombre',
						direction: 'ASC'
					},
					totalProperty: 'total',
					fields: ['id_accion','nombre'],
					// turn on remote sorting
					remoteSort: true,
					baseParams:{par_filtro:'acco.nombre'}
				}),
   				valueField: 'id_accion',
   				displayField: 'nombre',
   				gdisplayField:'desc_acciones',//mapea al store del grid
   			
   				hiddenName: 'id_acciones',
   				forceSelection:true,
   				typeAhead: true,
       			triggerAction: 'all',
       			lazyRender:true,
   				mode:'remote',
   				pageSize:10,
   				queryDelay:1000,
   				width:250,
   				gwidth:200,
   				minChars:2,
   				enableMultiSelect:true,
   				renderer:function (value, p, record){return String.format('{0}', record.data['desc_acciones']);}
   			},
   			type:'AwesomeCombo',
   			id_grupo:3,
   			/*filters:{	
   				        pfiltro:'acco.desc_acciones',
   						type:'string'
   					},*/
   		   
   			grid:true,
   			form:true
   	},
 

   	{
		config:{
			name:'id_correspondencias_asociadas',
			fieldLabel:'Responde a',
			allowBlank:true,
			emptyText:'Correspondencias...',
			store: new Ext.data.JsonStore({
			            url: '../../sis_correspondencia/control/Correspondencia/listarCorrespondenciaSimplificada',
						id: 'id_correspondencia',
						root: 'datos',
						sortInfo:{
							field: 'id_correspondencia',
							direction: 'desc'
						},
						totalProperty: 'total',
						fields: ['id_correspondencia','numero','referencia','desc_funcionario1'],
						// turn on remote sorting
						remoteSort: true,
						baseParams:{par_filtro:'cor.numero#cor.referencia#funcionario.desc_funcionario1'}
					}),
			valueField: 'id_correspondencia',
			displayField: 'numero',
			gdisplayField:'desc_asociadas',//mapea al store del grid
		
			hiddenName: 'id_correspondencias_asociadas',
			forceSelection:true,
			typeAhead: true,
			triggerAction: 'all',
			enableMultiSelect:true,
			lazyRender:true,
			mode:'remote',
			pageSize:10,
			queryDelay:1000,
			width:250,
			gwidth:200,
			minChars:2,
			renderer:function (value, p, record){return String.format('{0}', record.data['desc_asociadas']);}
		},
		type:'AwesomeCombo',
		id_grupo:2,
		/*filters:{	
			        pfiltro:'acco.desc_asociadas',
					type:'string'
				},*/
	   
		grid:false,
		form:true
},
	
{
			config:{
				name: 'nivel_prioridad',
				fieldLabel: 'Nivel de Prioridad',
				typeAhead: true,
				allowBlank:false,
	    		triggerAction: 'all',
	    		emptyText:'Seleccione Opcion...',
	    		selectOnFocus:true,
				mode:'local',
				//valorInicial:{ID:'interna',valor:'Interna'},
	   			store:new Ext.data.ArrayStore({
		        	fields: ['ID', 'valor'],
		        	data :	[['alta','Alta'],	
		        			['media','Media'],
		        			['baja','Baja']]
		        	}),
	    		valueField:'ID',
				displayField:'valor',
				width:150		
					
			},
			type:'ComboBox',
			valorInicial:'media',
			filters:{pfiltro:'cor.nivel_prioridad',type:'string'},
			id_grupo:2,
			grid:true,
			form:true
		},	

    	{
   			config:{
       		    name:'id_clasificador',
   				origen:'CLASIFICADOR',
   				fieldLabel: 'Clasificación',
   				gdisplayField:'desc_clasificador',//mapea al store del grid
   			    gwidth:200,
   				renderer:function(value, p, record){return String.format('{0}', record.data['desc_clasificador']);}
		
       	     },
   			type:'ComboRec',
   			id_grupo:2,
   			filters:{	
		        pfiltro:'tclasificador.nombre',
				type:'string'
			},
   		   
   			grid:true,
   			form:true
   	      }
		,	
		{
			config:{
				name: 'respuestas',
				fieldLabel: 'respuestas',
				allowBlank: true,
				gwidth: 150,
				maxLength:500
			},
			type:'TextField',
			filters:{pfiltro:'cor.respuestas',type:'string'},
			id_grupo:1,
			grid:false,
			form:false
		},
		{
			config:{
				name: 'fecha_reg',
				fieldLabel: 'Fecha creación',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				renderer:function (value,p,record){return value?value.dateFormat('d/m/Y h:i:s'):''}
			},
			type:'DateField',
			gwidth: 100,
			filters:{pfiltro:'cor.fecha_reg',type:'date'},
			id_grupo:2,
			grid:true,
			form:false
		},
		
		{
			config:{
				name: 'usr_reg',
				fieldLabel: 'Creado por',
				allowBlank: true,
				
				gwidth: 100,
				maxLength:4
			},
			type:'TextField',
			filters:{pfiltro:'usu1.cuenta',type:'string'},
			id_grupo:2,
			grid:true,
			form:false
		},
		
		{
			config:{
				name: 'estado_reg',
				fieldLabel: 'Estado Reg.',
				allowBlank: false,
				
				gwidth: 100,
				maxLength:10
			},
			type:'TextField',
			filters:{pfiltro:'cor.estado_reg',type:'string'},
			id_grupo:2,
			grid:true,
			form:false
		},
		{
			config:{
				name: 'fecha_mod',
				fieldLabel: 'Fecha Modif.',
				allowBlank: true,
			
				gwidth: 100,
				renderer:function (value,p,record){return value?value.dateFormat('d/m/Y h:i:s'):''}
			},
			type:'DateField',
			filters:{pfiltro:'cor.fecha_mod',type:'date'},
			id_grupo:2,
			grid:true,
			form:false
		},
		{
			config:{
				name: 'usr_mod',
				fieldLabel: 'Modificado por',
				allowBlank: true,
				
				gwidth: 100,
				maxLength:4
			},
			type:'TextField',
			filters:{pfiltro:'usu2.cuenta',type:'string'},
			id_grupo:12,
			grid:true,
			form:false
		}
	],
	title:'Correspondencia',
	ActSave:'../../sis_correspondencia/control/Correspondencia/insertarCorrespondencia',
	ActDel:'../../sis_correspondencia/control/Correspondencia/eliminarCorrespondencia',
	ActList:'../../sis_correspondencia/control/Correspondencia/listarCorrespondencia',
	id_store:'id_correspondencia',
	fields: [
		'id_correspondencia',
		'estado', 
		'estado_reg',
		{name:'fecha_documento', type: 'date', dateFormat:'Y-m-d'},
		{name:'fecha_fin', type: 'date', dateFormat:'Y-m-d H:i:s'},
		'id_acciones',
		'id_archivo', 
		'id_correspondencia_fk', 
		'id_correspondencias_asociadas', 
		'id_depto', 
		'id_documento', 
		'id_funcionario', 
		'id_gestion',
		'id_institucion', 
		'id_periodo',
		'id_persona',
		'id_uo', 
		'mensaje', 
		'nivel', 
		'nivel_prioridad', 
		'numero',
		'observaciones_estado',
		'referencia', 
		'respuestas',
		'sw_responsable', 
		'tipo',
		{name:'fecha_reg', type: 'date', dateFormat:'Y-m-d H:i:s'},
		'id_usuario_reg', 
		{name:'fecha_mod', type: 'date', dateFormat:'Y-m-d H:i:s'},
		'id_usuario_mod','usr_reg', 
		'usr_mod','cite','desc_depto','desc_documento','desc_funcionario', 'ruta_archivo','version',
		'desc_uo',
		'id_clasificador','desc_clasificador', 
	],
	sortInfo:{
		field: 'id_correspondencia',
		direction: 'desc'
	},
	Grupos: [
	            {
	                layout: 'column',
	                border: false,
	                defaults: {
	                   border: false
	                },            
	                items: [
	                        {
	                        bodyStyle: 'padding-left:5px;padding-left:5px;',
	                        border: false,
	                        defaults: {
		     	                   border: false
		     	                },   
					        width: 500,
					        items: [
					           
	    	               			 {
	                	          	bodyStyle: 'padding-left:5px;padding-left:5px;',
						          	 items: [{
								            xtype: 'fieldset',
								            title: 'Parametros',
								           
								            //autoHeight: true,
								            items: [],
									        id_grupo:0
								        }]
								    },{
								    	 bodyStyle: 'padding-left:5px;padding-left:5px;',
								        items: [{
								            xtype: 'fieldset',
								            title: 'Datos Remitente',
								          
								           // autoHeight: true,
								            items: [],
									        id_grupo:1
								        }]
								    },
								    {
								        bodyStyle: 'padding-left:5px;padding-left:5px;',
								        items: [{
								            xtype: 'fieldset',
								          
								            title: 'Datos Destinatario',
								           // autoHeight: true,
								            items: [],
									        id_grupo:3
								        }]
								    } 
					        ]
					        },
						    {
						        bodyStyle: 'padding-left:5px;padding-left:5px;',
						        border: false,
						        hidden:false,
						        width: 500,
						        items: [{
						            xtype: 'fieldset',
						            title: 'Mensaje',
						            //autoHeight: true,
						            items: [],
							        id_grupo:2
						        }]
						    } ]
	            }
	           
	        ],

	     loadValoresIniciales:function(){

		Phx.vista.Correspondencia.superclass.loadValoresIniciales.call(this);

		 



        }, 
        
    onButtonNew:function(){
    	 var cmbDoc = this.getComponente('id_documento');
		 var cmpFuncionarios = this.getComponente('id_funcionarios');
	     var cmpInstitucion = this.getComponente('id_institucion');
		 var cmpPersona = this.getComponente('id_persona');
		 
    	 Phx.vista.Correspondencia.superclass.onButtonNew.call(this);
      	 this.adminGrupo({mostrar:[0,1,2,3]});
      	 this.ocultarComponente(cmpInstitucion);
	     this.ocultarComponente(cmpPersona);
	     this.mostrarComponente(cmpFuncionarios);
	     
	     this.getComponente('id_uo').enable();
	     this.getComponente('id_clasificador').enable();
	     this.getComponente('mensaje').enable();
	     this.getComponente('nivel_prioridad').enable();
	     this.getComponente('referencia').enable();
	    
	     cmbDoc.store.baseParams.tipo='interna';//valor por dfecto es interna	
	     cmbDoc.modificado = true;
	     cmbDoc.reset();
      	
     },
    
    onButtonEdit:function(){
    	Phx.vista.Correspondencia.superclass.onButtonEdit.call(this);
      	this.adminGrupo({mostrar:[2],ocultar:[0,1,3]});
      	this.getComponente('id_uo').disable();
      	 var data=this.sm.getSelected().data;
      	 console.log(data,data.estado)
      	if(data.estado=='borrador_envio')
      	{
	      	this.getComponente('id_clasificador').enable();
	      	this.getComponente('mensaje').enable();
		    this.getComponente('nivel_prioridad').enable();
		    this.getComponente('referencia').enable(); 
	     }
	     else if(data.estado=='enviado')
	     {
	     	this.getComponente('id_clasificador').disable();
	      	this.getComponente('mensaje').disable();
		    this.getComponente('nivel_prioridad').disable();
		    this.getComponente('referencia').disable();
	     	
	     }
     },
        
 	verCorrespondencia:function(){
		var rec=this.sm.getSelected();
		window.open('../'+rec.data.ruta_archivo);
	},
	
	onButtonCorregir:function(){
		var id_correspondencia=this.sm.getSelected().data.id_correspondencia;
			Phx.CP.loadingShow();
		    Ext.Ajax.request({
				// form:this.form.getForm().getEl(),
				url:'../../sis_correspondencia/control/Correspondencia/corregirCorrespondencia',
				params:{id_correspondencia:id_correspondencia},
				success:this.successDerivar,
				failure: this.conexionFailure,
				timeout:this.timeout,
				scope:this
			});	
	},
	
	onButtonMandar:function(){
	   
	   var data=this.sm.getSelected().data.id_proceso_contrato;
			var id_correspondencia=this.sm.getSelected().data.id_correspondencia;
			Phx.CP.loadingShow();
		    Ext.Ajax.request({
				// form:this.form.getForm().getEl(),
				url:'../../sis_correspondencia/control/Correspondencia/derivarCorrespondencia',
				params:{id_correspondencia:id_correspondencia},
				success:this.successDerivar,
				failure: this.conexionFailure,
				timeout:this.timeout,
				scope:this
			});	
	},
	successDerivar:function(resp){
			
			Phx.CP.loadingHide();
			var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
			if(!reg.ROOT.error){
				alert(reg.ROOT.detalle.mensaje)
				
			}else{
				
				alert('ocurrio un error durante el proceso')
			}
			this.reload();
			
		},
        
      preparaMenu:function(n){
      	
      	Phx.vista.Correspondencia.superclass.preparaMenu.call(this,n);
      	
		  var data = this.getSelectedData();
		  var tb =this.tbar;
		  //si el archivo esta escaneado se permite visualizar
		  if(data['version']>0){
		  	   this.getBoton('verCorrespondencia').enable();
		       this.getBoton('mandar').enable()
	  		}
	  		else
	  		{
	  			this.getBoton('verCorrespondencia').disable();
	  			this.getBoton('mandar').disable()
	  			
	  		}
	 
		  
		  //cuando el conrtato esta registrado el abogado no puede hacerle mas cambios
		  if(data['estado']=='borrador_envio'){
		  		
			  	this.getBoton('aSubirCorrespondencia').enable();
			  		this.getBoton('corregir').disable();
		  } 
		  if(data['estado']=='enviado'){
		  		if(tb){
			  		
			  		this.getBoton('aSubirCorrespondencia').disable();
			  		this.getBoton('corregir').enable();
			  		
			  		
			  		
			  	}
			  	
		  } 
		  
		  
		  return tb
		
	},     
	iniciarEventos:function(){ 
	

		var cmpFuncionarios = this.getComponente('id_funcionarios');
		var cmpInstitucion = this.getComponente('id_institucion');
		var cmpPersona = this.getComponente('id_persona');
		var cmbDoc = this.getComponente('id_documento');
		
		
	    //para habilitar el tipo de correspondecia para el sistema corres

		this.getComponente('tipo').on('select',function(combo,record,index){
                //actualiza combos de documento segun el tipo
        		cmbDoc.store.baseParams.tipo=record.data.ID;	
                 cmbDoc.modificado = true;
                 cmbDoc.reset();

                 if(record.data.ID=='interna'){
                	 this.ocultarComponente(cmpInstitucion);
                	 this.ocultarComponente(cmpPersona);
                	 this.mostrarComponente(cmpFuncionarios);
                	 cmpPersona.reset();
                	 cmpInstitucion.reset();
                	 
                     }
                 else{
                	 this.mostrarComponente(cmpInstitucion);
                	 this.mostrarComponente(cmpPersona);
                	 this.ocultarComponente(cmpFuncionarios);
                	 cmpFuncionarios.reset();

                     }
        		
    			
		},this);

	},
	 

}
)
</script>

