<?php
header("content-type: text/javascript; charset=UTF-8");
?>

<script>
Phx.vista.Proveido = Ext.extend(Phx.frmInterfaz,{
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
		Phx.vista.Proveido.superclass.constructor.call(this,config);
		this.init(); 
	},
	
	loadValoresIniciales:function(){    	
		Phx.vista.Proveido.superclass.loadValoresIniciales.call(this);		
        this.Cmp.id_correspondencia.setValue(this.id_correspondencia);
        this.Cmp.id_origen.setValue(this.id_origen);
	},

	Atributos:[
		{
			config : {
				labelSeparator : '',
				inputType : 'hidden',
				name : 'id_correspondencia'
			},
			type : 'Field',
			form : true
        },
        {
			config : {
				labelSeparator : '',
				inputType : 'hidden',
				name : 'id_origen'
			},
			type : 'Field',
			form : true
		},		
		{
            config : {
                name : 'proveido',
                fieldLabel : 'Comentario',
                allowBlank : true,   
                width:200                
            },
            type : 'TextArea',           
            form : true,
        },
	],
	labelSubmit: '<i class="fa fa-check"></i> Guardar',
	title: 'Comentario',
	// Funcion guardar del formulario
	onSubmit: function(o) {
		var me = this;
		if (me.form.getForm().isValid()) {
			var parametros = me.getValForm()
			Phx.CP.loadingShow();
			Ext.Ajax.request({
                url : '../../sis_correspondencia/control/Correspondencia/derivarCorrespondencia',
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