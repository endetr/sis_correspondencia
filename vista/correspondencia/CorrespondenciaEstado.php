a<?php
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
    Phx.vista.CorrespondenciaEstado = {
        bsave:false,
        bnew:false,
        bedit:false,
        bdel:false,
        require: '../../../sis_correspondencia/vista/correspondencia/Correspondencia.php',
        requireclase: 'Phx.vista.Correspondencia',
        title: 'Correspondencia Estado',
        nombreVista: 'CorrespondenciaEstado',
        swTipo: 'externa',
        gruposBarraTareas: [
            {
                name: 'externa',
                title: '<H1 align="center"><i class="fa fa-thumbs-o-down"></i> Externa</h1>',
                grupo: 0,
                height: 0
            },{
                name: 'interna',
                title: '<H1 align="center"><i class="fa fa-eye"></i> Interna</h1>',
                grupo: 1,
                height: 0
            },{
                name: 'saliente',
                title: '<H1 align="center"><i class="fa fa-eye"></i> Emitida Externa</h1>',
                grupo: 1,
                height: 0
            }
        ],
        beditGroups: [0, 1],
        bactGroups: [0, 1],
        btestGroups: [0,1],
        bexcelGroups: [0, 1],
        ActList:'../../sis_correspondencia/control/Correspondencia/listarCorrespondenciaEstado',
        constructor: function(config) {
            //this.initButtons=[this.cmbGestion, this.cmbPeriodo];

            Phx.vista.CorrespondenciaEstado.superclass.constructor.call(this,config);
            this.maestro=config.maestro;

            this.getBoton('ImpCodigo').hide();
            this.getBoton('Plantilla').hide();
            this.getBoton('Habilitar').hide();
            this.getBoton('FinalizarExterna').hide();
            this.getBoton('SubirDocumento').hide();
            this.getBoton('ImpCodigoDoc').hide();
            this.getBoton('Derivar').hide();
            this.getBoton('Finalizar').hide();
            this.getBoton('Archivar').hide();
            this.getBoton('Corregir').hide();
            this.getBoton('HojaRuta').hide();
            this.getBoton('Historico').show();
            this.getBoton('Adjuntos').show();
            this.init();
            this.store.baseParams = {'interface': 'recibida_archivada','tipo': this.swTipo};
            this.load({params: {start: 0, limit: 50}})
            //  this.grid.on('cellclick', this.abrirEnlace, this);
            //this.load({params: {start: 0, limit: 50}})
            /*this.cmbGestion.on('select', function(combo, record, index){
                this.tmpGestion = record.data.gestion;
                this.cmbPeriodo.enable();
                this.cmbPeriodo.reset();
                this.store.removeAll();
                this.cmbPeriodo.store.baseParams = Ext.apply(this.cmbPeriodo.store.baseParams, {id_gestion: this.cmbGestion.getValue()});
                this.cmbPeriodo.modificado = true;
            },this);

            this.cmbPeriodo.on('select', function( combo, record, index){
                this.tmpPeriodo = record.data.periodo;
                //this.capturaFiltros();
            },this);*/
        },
        //
        getParametrosFiltro: function () {
            this.store.baseParams.tipo = this.swTipo;
        },
        //
        actualizarSegunTab: function (name, indice) {
            this.getBoton('ImpCodigo').hide();
            this.getBoton('Plantilla').hide();
            this.getBoton('Habilitar').hide();
            this.getBoton('FinalizarExterna').hide();
            this.getBoton('SubirDocumento').hide();
            this.getBoton('ImpCodigoDoc').hide();
            this.getBoton('FinalizarExterna').hide();
            this.getBoton('SubirDocumento').hide();
            this.getBoton('ImpCodigoDoc').hide();
            this.getBoton('Derivar').hide();
            this.getBoton('Finalizar').hide();
            this.getBoton('Archivar').hide();
            this.getBoton('Corregir').hide();
            this.getBoton('HojaRuta').hide();
            this.getBoton('Historico').show();
            this.getBoton('Adjuntos').show();
            this.swTipo = name;
            this.getParametrosFiltro();
            this.load();
        },
        //
        /*cmbGestion: new Ext.form.ComboBox({
            fieldLabel: 'Gestion',
            allowBlank: false,
            emptyText:'Gestion...',
            blankText: 'Año',
            store:new Ext.data.JsonStore(
                {
                    url: '../../sis_parametros/control/Gestion/listarGestion',
                    id: 'id_gestion',
                    root: 'datos',
                    sortInfo:{
                        field: 'gestion',
                        direction: 'DESC'
                    },
                    totalProperty: 'total',
                    fields: ['id_gestion','gestion'],
                    // turn on remote sorting
                    remoteSort: true,
                    baseParams:{par_filtro:'gestion'}
                }),
            valueField: 'id_gestion',
            triggerAction: 'all',
            displayField: 'gestion',
            hiddenName: 'id_gestion',
            mode:'remote',
            pageSize:50,
            queryDelay:500,
            listWidth:'280',
            width:80
        }),
        //
        cmbPeriodo: new Ext.form.ComboBox({
            fieldLabel: 'Periodo',
            allowBlank: false,
            blankText : 'Mes',
            emptyText:'Periodo...',
            store:new Ext.data.JsonStore(
                {
                    url: '../../sis_parametros/control/Periodo/listarPeriodo',
                    id: 'id_periodo',
                    root: 'datos',
                    sortInfo:{
                        field: 'periodo',
                        direction: 'ASC'
                    },
                    totalProperty: 'total',
                    fields: ['id_periodo','periodo','id_gestion','literal'],
                    // turn on remote sorting
                    remoteSort: true,
                    baseParams:{par_filtro:'gestion'}
                }),
            valueField: 'periodo',
            triggerAction: 'all',
            displayField: 'literal',
            hiddenName: 'id_periodo',
            mode:'remote',
            pageSize:50,
            disabled: true,
            queryDelay:500,
            listWidth:'280',
            width:100
        }),*/
        //
        abrirEnlace: function(cell,rowIndex,columnIndex,e){
            if(cell.colModel.getColumnHeader(columnIndex) === 'numero'){
                var rec = this.sm.getSelected();
                var remitente;
                if (rec.data.desc_insti==null){
                    remitente=rec.data.nombre_completo1;
                }else{
                    remitente=rec.data.desc_insti;
                }
                Phx.CP.loadWindows('../../../sis_correspondencia/vista/correspondencia/Historico.php',remitente+' -- '+rec.data.numero, {
                    width : 900,
                    height : 400
                }, rec.data, this.idContenedor, 'Historico')
            }
        },
        //
        preparaMenu:function(n){
            Phx.vista.CorrespondenciaEstado.superclass.preparaMenu.call(this,n);
            var data = this.getSelectedData();
            var tb =this.tbar;
            console.log('tb',data['tipo']);
            if (data['tipo'] == 'saliente') {
                if (tb) {
                    this.getBoton('Adjuntos').enable();
                }
            }
            return tb
        },
        //
        east : undefined
    };
</script>
