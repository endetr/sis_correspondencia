<?php
/*
*/

header("content-type: text/javascript; charset=UTF-8");
?>

<script>
Phx.vista.Filtro=Ext.extend(Phx.frmInterfaz,{
	
    constructor:function(config)
    {   
    	this.panelResumen = new Ext.Panel({html:''});
    	this.Grupos = 
    	[
            {
                xtype: 'fieldset',
                border: true,
                autoScroll: true,
                layout: 'form',
                items:
                [
                ],
                id_grupo: 0
            },
            this.panelResumen
	    ];
				    
        Phx.vista.Filtro.superclass.constructor.call(this,config);
        this.init(); 
        this.iniciarEventos(); 
        
        if(config.detalle){        
			//cargar los valores para el filtro
			this.loadForm({data: config.detalle});
			var me = this;
			setTimeout(function(){
				me.onSubmit()
			}, 1500);			
		}	
    },
    //  
    Atributos:[        
        {
            config:{
                name : 'id_gestion',
                origen : 'GESTION',
                fieldLabel : 'Gestion',
                gdisplayField: 'desc_gestion',
                allowBlank : true,
                width: 150
            },
            type : 'ComboRec',
            id_grupo : 0,
            form : true
        },{
            config:{
                name: 'desde',
                fieldLabel: 'Fecha Recepcion (Desde)',
                allowBlank: true,
                format: 'd/m/Y',
                width: 150
            },
            type: 'DateField',
            id_grupo: 0,
            form: true
        },
        {
            config:{
                name: 'hasta',
                fieldLabel: 'Fecha Documento (Hasta)',
                allowBlank: true,
                format: 'd/m/Y',
                width: 150
            },
            type: 'DateField',
            id_grupo: 0,
            form: true
        },
        /*{
            config : {
                name : 'id_institucion_destino',
                allowBlank : true,
                fieldLabel : 'Institucion',
                valueField : 'id_institucion',                
                tinit : true,
                tasignacion:false,
                origen : 'INSTITUCION',
                gdisplayField : 'desc_insti',                
                width : 150,
                renderer : function(value, p, record) {
                    return String.format('{0}', record.data['desc_insti']);
                }
            },
            type : 'ComboRec',
            id_grupo : 0,
            filters : {
                pfiltro : 'insti.nombre',
                type : 'string'
            },          
            form : true
        },*/
        {
			config: {
				name: 'cite',
				allowBlank: true,
				fieldLabel: 'Cite',
				width: 150
			},
			type: 'Field',
			id_grupo: 0,
			form: true
        },
        {
			config: {
				name: 'numero',
				allowBlank: true,
				fieldLabel: 'Nro. Tramite',
				width: 150
			},
			type: 'Field',
			id_grupo: 0,
			form: true
        },        
        {
			config: {
				name: 'observacion',
				allowBlank: true,
				fieldLabel: 'Observacion',
				width: 150
			},
			type: 'Field',
			id_grupo: 0,
			form: true
        },   
        {
			config: {
				name: 'referencia',
				allowBlank: true,
				fieldLabel: 'Referencia',
				width: 150
			},
			type: 'Field',
			id_grupo: 0,
			form: true
        },   
	],
	labelSubmit: '<i class="fa fa-check"></i> Aplicar Filtro',
	title: 'Filtro',
	autoScroll: true,
    collapsed: true,
    collapsible: true,
    fwidth:200,
    //
    onSubmit:function(){
        //TODO passar los datos obtenidos del wizard y pasar  el evento save		
        var me = this;
        console.log('meme', me);
		if (this.form.getForm().isValid()) {
            var parametros = me.getValForm();
			this.fireEvent('beforesave',this,this.getValues());
            this.getValues();            
            this.onEnablePanel(me.idContenedorPadre, parametros)
		}
	},
    //
    getValues:function(){		        
        //var desc=this.Cmp.id_institucion_destino.lastSelectionText;        
		var resp = {			
            id_gestion : this.Cmp.id_gestion.getValue(),
            desde : this.Cmp.desde.getValue(),
            hasta : this.Cmp.hasta.getValue(),
            cite : this.Cmp.cite.getValue(),
            numero : this.Cmp.numero.getValue(),
            observacion : this.Cmp.observacion.getValue(),
            referencia : this.Cmp.referencia.getValue(),
            //id_institucion_destino : this.Cmp.id_institucion_destino.getValue(),
            //desc_insti: desc,
        }
        console.log('res',resp);
		return resp;
	},
    //
    loadValoresIniciales: function(){
    	Phx.vista.Filtro.superclass.loadValoresIniciales.call(this);    	
    },
    onReloadPage:function(){

    }     
    
})    
</script>
