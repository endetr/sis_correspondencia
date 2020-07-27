<?php
/**
*/
header("content-type: text/javascript; charset=UTF-8");
?>

<script>
Phx.vista.adicionarCorrespondencia = Ext.extend(Phx.frmInterfaz,{
	constructor:function(config)
	{	
		this.panelResumen = new Ext.Panel({html:''});
		this.Grupos = [{
			xtype: 'fieldset',
			border: false,
			autoScroll: true,
			layout: 'form',
			items: [],
			id_grupo: 0
		},this.panelResumen
		];
        Phx.vista.adicionarCorrespondencia.superclass.constructor.call(this,config);           
        this.init(); 
        this.iniciarEventos();
	},
		
    iniciarEventos: function(config){
        Phx.vista.adicionarCorrespondencia.superclass.iniciarEventos.call(this,config);

        this.tipo = this.getComponente('tipo');
		var cmbDoc = this.getComponente('id_documento');

		cmbDoc.store.baseParams.tipo = 'entrante';
		cmbDoc.modificado = true;
		cmbDoc.reset();

        this.Cmp.id_depto.store.load({params:{start:0,limit:30},
            callback : function (r) {
				this.Cmp.id_depto.setValue(r[0].data.id_depto);				
            }, scope : this
		});  
		//
		this.Cmp.id_documento.store.load({params:{start:0,limit:30},
            callback : function (r) {
                this.Cmp.id_documento.setValue(r[0].data.id_documento);
            }, scope : this
		}); 
    },

	Atributos:[
        {
            config : {
                name : 'tipo',
                fieldLabel : 'Tipo Correspondencia',
                typeAhead : true,
                allowBlank : false,
                triggerAction : 'all',
                emptyText : 'Seleccione Opcion...',
                selectOnFocus : true,
                width : 200,
                mode : 'local',
                store : new Ext.data.ArrayStore({
                    fields : ['ID', 'valor'],
                    data : [['interna', 'Interna'], ['saliente', 'Saliente'], ['externa', 'Externa']],
                }),
                valueField : 'ID',
                displayField : 'valor'
            },
            type : 'ComboBox',
            valorInicial : 'externa',
            filters : {
                pfiltro : 'cor.tipo',
                type : 'string'
            },
            id_grupo : 0,
            form : false
        },{
            config : {
                name : 'id_depto',
                hiddenName : 'id_depto',
                url : this.urlDepto,
                origen : 'DEPTO',
                allowBlank : false, //#6
                fieldLabel : 'Depto Corres.', //#6
                gdisplayField : 'desc_depto', //dibuja el campo extra de la consulta al hacer un inner join con orra tabla
                width : 200,
                gwidth : 180,
                baseParams : {
                    estado : 'activo',
                    codigo_subsistema : 'CORRES'
                }, 
                renderer : function(value, p, record) {
                    return String.format('{0}', record.data['desc_depto']);
                }
            },
            valorInicial: 'Correspondencia Oficina Central',
            type : 'ComboRec',
            id_grupo : 0,
            filters : {
                pfiltro : 'incbte.desc_depto',
                type : 'string'
            },
            form : true
        },{
            config : {
                name : 'id_documento',
                fieldLabel : 'Tipo Documento', 
                allowBlank : false,
                emptyText : 'Documento...',
                store : new Ext.data.JsonStore({
                    url : '../../sis_parametros/control/Documento/listarDocumento',
                    id : 'id_documento',
                    root : 'datos',
                    sortInfo : {
                        field : 'codigo',
                        direction : 'ASC'
                    },
                    totalProperty : 'total',
                    fields : ['id_documento', 'codigo', 'descripcion'],            
                    remoteSort : true,
                    baseParams : {
                        par_filtro : 'DOCUME.codigo#DOCUME.descripcion'
                    }
                }),
                valueField : 'id_documento',
                displayField : 'descripcion',
                gdisplayField : 'desc_documento',
                tpl : '<tpl for="."><div class="x-combo-list-item"><p>({codigo}) {descripcion}</p> </div></tpl>',
                hiddenName : 'id_documento',
                forceSelection : true,
                typeAhead : true,
                triggerAction : 'all',
                lazyRender : true,
                mode : 'remote',
                pageSize : 10,
                queryDelay : 1000,
                width : 200,
                gwidth : 150,
                minChars : 2,
                renderer : function(value, p, record) {
                    return String.format('{0}', record.data['desc_documento']);
                }
            },
            type : 'ComboBox',
            id_grupo : 0,
            filters : {
                pfiltro : 'doc.descripcion',
                type : 'string'
            },
            form : true
        },
	],
	labelSubmit: '<i class="fa fa-check"></i> Guardar',
	title: 'Adicionar....',
	// Funcion guardar del formulario
	onSubmit: function(o) {
		var me = this;
		if (me.form.getForm().isValid()) {
			var parametros = me.getValForm()
			Phx.CP.loadingShow();
			Ext.Ajax.request({
				url: '../../sis_correspondencia/control/Correspondencia/adicionarCorrespondencia',
				params : parametros,
				success : this.successGen,
				failure : this.conexionFailure,
				timeout : this.timeout,
				scope : this
			})
		}
	},
	
	successGen: function(resp){
		Phx.CP.loadingHide();
		Phx.CP.getPagina(this.idContenedorPadre).reload();
		this.panel.destroy();
	}

})
</script>