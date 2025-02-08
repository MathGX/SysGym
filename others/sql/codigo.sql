--AGREGADO REFERENCIAL DE SERVICIOS
CREATE TABLE public.horarios_comida (
                hrcom_cod INTEGER NOT NULL,
                hrcom_descri VARCHAR NOT NULL,
                hrcom_estado VARCHAR NOT NULL,
                CONSTRAINT horarios_comida_pk PRIMARY KEY (hrcom_cod)
);

--AGREGADO REFERENCIAL DE SERVICIOS
CREATE TABLE public.unidad_medida (
                uni_cod INTEGER NOT NULL,
                uni_descri VARCHAR NOT NULL,
                uni_estado VARCHAR NOT NULL,
                CONSTRAINT unidad_medida_pk PRIMARY KEY (uni_cod)
);

--AGREGADO REFERENCIAL DE SERVICIOS
CREATE TABLE public.parametros_medicion (
                param_cod INTEGER NOT NULL,
                param_descri VARCHAR NOT NULL,
                param_estado VARCHAR NOT NULL,
                CONSTRAINT parametros_medicion_pk PRIMARY KEY (param_cod)
);

--AGREGADO REFERENCIAL DE SERVICIOS
CREATE TABLE public.comidas (
                comi_cod INTEGER NOT NULL,
                comi_descri VARCHAR NOT NULL,
                comi_estado VARCHAR NOT NULL,
                CONSTRAINT comidas_pk PRIMARY KEY (comi_cod)
);

--AGREGADO REFERENCIAL DE SEGURIDAD
CREATE TABLE public.acceso (
                acc_cod INTEGER NOT NULL,
                acc_usu VARCHAR NOT NULL,
                acc_fecha DATE NOT NULL,
                acc_hora TIME NOT NULL,
                acc_obs VARCHAR NOT NULL,
                CONSTRAINT acceso_pk PRIMARY KEY (acc_cod)
);

--AGREGADO REFERNCIAL DE SEGURIDAD
CREATE TABLE public.modulos (
                mod_cod INTEGER NOT NULL,
                mod_descri VARCHAR NOT NULL,
                mod_estado VARCHAR NOT NULL,
                CONSTRAINT modulos_pk PRIMARY KEY (mod_cod)
);

--AGREGADO REFERNCIAL DE SEGURIDAD
CREATE TABLE public.GUI (
                gui_cod INTEGER NOT NULL,
                mod_cod INTEGER NOT NULL,
                gui_descri VARCHAR NOT NULL,
                gui_estado VARCHAR NOT NULL,
                CONSTRAINT gui_pk PRIMARY KEY (gui_cod, mod_cod)
);

--AGREGADO REFERENCIAL SEGURIDAD
CREATE TABLE public.permisos (
                permi_cod INTEGER NOT NULL,
                permi_descri VARCHAR NOT NULL,
                permi_estado VARCHAR NOT NULL,
                CONSTRAINT permisos_pk PRIMARY KEY (permi_cod)
);

--AGREGADO REFERNCIAL DE SEGURIDAD
CREATE TABLE public.perfiles (
                perf_cod INTEGER NOT NULL,
                perf_descri VARCHAR NOT NULL,
                perf_estado VARCHAR NOT NULL,
                CONSTRAINT perfiles_pk PRIMARY KEY (perf_cod)
);

--AGREGADO REFERNCIAL DE SEGURIDAD
CREATE TABLE public.gui_perfiles (
                perf_cod INTEGER NOT NULL,
                gui_cod INTEGER NOT NULL,
                mod_cod INTEGER NOT NULL,
                guiperf_estado VARCHAR NOT NULL,
                CONSTRAINT gui_perfiles_pk PRIMARY KEY (perf_cod, gui_cod, mod_cod)
);

--AGREGADO REFERNCIAL DE SEGURIDAD
CREATE TABLE public.perfiles_permisos (
                perf_cod INTEGER NOT NULL,
                permi_cod INTEGER NOT NULL,
                perfperm_estado VARCHAR NOT NULL,
                CONSTRAINT perfiles_permisos_pk PRIMARY KEY (perf_cod, permi_cod)
);

--AGREGADO REFERENCIAL DE SERVICIOS 
CREATE TABLE public.tipo_rutinas (
                tiprut_cod INTEGER NOT NULL,
                tiprut_descri VARCHAR NOT NULL,
                tiprut_estado VARCHAR NOT NULL,
                CONSTRAINT tipo_rutinas_pk PRIMARY KEY (tiprut_cod)
);

--AGREGADO REFERENCIAL DE SERVICIOS 
CREATE TABLE public.tipo_plan_alimenticio (
                tiplan_cod INTEGER NOT NULL,
                tiplan_descri VARCHAR NOT NULL,
                tiplan_estado VARCHAR NOT NULL,
                CONSTRAINT tipo_plan_alimenticio_pk PRIMARY KEY (tiplan_cod)
);

--AGREGADO REFERENCIAL DE SERVICIOS
CREATE TABLE public.presupuesto_venta (
                preven_cod INTEGER NOT NULL,
                ven_cod INTEGER NOT NULL,
                prpr_cod INTEGER NOT NULL,
                CONSTRAINT presupuesto_venta_pk PRIMARY KEY (preven_cod, ven_cod, prpr_cod)
);

--AGREGADO REFERENCIAL DE SERVICIOS 
CREATE TABLE public.ejercicios (
                ejer_cod INTEGER NOT NULL,
                ejer_descri VARCHAR NOT NULL,
                ejer_estado VARCHAR NOT NULL,
                CONSTRAINT ejercicios_pk PRIMARY KEY (ejer_cod)
);

--AGREGADO REFERENCIAL DE SERVICIOS 
CREATE TABLE public.dias (
                dia_cod INTEGER NOT NULL,
                dia_descri VARCHAR NOT NULL,
                dia_estado VARCHAR NOT NULL,
                CONSTRAINT dias_pk PRIMARY KEY (dia_cod)
);

--AGREGADO REFERENCIAL DE SERVICIOS 
CREATE TABLE public.tipo_equipos (
                tipequi_cod INTEGER NOT NULL,
                tipequi_descri VARCHAR NOT NULL,
                tipequi_estado VARCHAR NOT NULL,
                CONSTRAINT tipo_equipos_pk PRIMARY KEY (tipequi_cod)
);

--AGREGADO REFERENCIAL DE SERVICIOS 
CREATE TABLE public.equipos (
                equi_cod INTEGER NOT NULL,
                tipequi_cod INTEGER NOT NULL,
                equi_estado VARCHAR NOT NULL,
                equi_descri VARCHAR NOT NULL,
                CONSTRAINT equipos_pk PRIMARY KEY (equi_cod, tipequi_cod)
);

--AGREGADO REFERNCIAL DE SERVICIOS
CREATE TABLE public.cargos (
                car_cod INTEGER NOT NULL,
                car_descri VARCHAR NOT NULL,
                car_estado VARCHAR NOT NULL,
                CONSTRAINT cargos_pk PRIMARY KEY (car_cod)
);

-- AGREGADO REFERNCIAL DE VENTAS
CREATE TABLE public.marca_tarjeta (
                martarj_cod INTEGER NOT NULL,
                martarj_descri VARCHAR NOT NULL,
                martarj_estado VARCHAR NOT NULL,
                CONSTRAINT marca_tarjeta_pk PRIMARY KEY (martarj_cod)
);

-- AGREGADO REFERNCIAL DE VENTAS
CREATE TABLE public.forma_cobro (
                forcob_cod INTEGER NOT NULL,
                forcob_descri VARCHAR NOT NULL,
                forcob_estado VARCHAR NOT NULL,
                CONSTRAINT forma_cobro_pk PRIMARY KEY (forcob_cod)
);

-- AGREGADO REFERNCIAL DE VENTAS
CREATE TABLE public.entidad_emisora (
                ent_cod INTEGER NOT NULL,
                ent_razonsocial VARCHAR NOT NULL,
                ent_ruc VARCHAR NOT NULL,
                ent_telf VARCHAR NOT NULL,
                ent_email VARCHAR NOT NULL,
                ent_estado VARCHAR NOT NULL,
                CONSTRAINT entidad_emisora_pk PRIMARY KEY (ent_cod)
);

-- AGREGADO REFERNCIAL DE VENTAS
CREATE TABLE public.entidad_adherida (
                martarj_cod INTEGER NOT NULL,
                ent_cod INTEGER NOT NULL,
                entahd_cod INTEGER NOT NULL,
                entahd_estado VARCHAR NOT NULL,
                CONSTRAINT entidad_adherida_pk PRIMARY KEY (martarj_cod, ent_cod, entahd_cod)
);

-- AGREGADO REFERNCIAL DE VENTAS
CREATE TABLE public.tipo_comprobante (
                tipcomp_cod INTEGER NOT NULL,
                tipcomp_descri VARCHAR NOT NULL,
                tipcomp_estado VARCHAR NOT NULL,
                CONSTRAINT tipo_comprobante_pk PRIMARY KEY (tipcomp_cod)
);

-- AGREGADO REFERNCIAL DE VENTAS
CREATE TABLE public.tipo_documento (
                tipdoc_cod INTEGER NOT NULL,
                tipdoc_descri VARCHAR NOT NULL,
                tipdoc_estado VARCHAR NOT NULL,
                CONSTRAINT tipo_documento_pk PRIMARY KEY (tipdoc_cod)
);

-- AGREGADO REFERNCIAL DE SERVICIOS
CREATE TABLE public.personas (
                per_cod INTEGER NOT NULL,
                per_nombres VARCHAR NOT NULL,
                per_apellidos VARCHAR NOT NULL,
                per_nrodoc VARCHAR NOT NULL,
                per_telefono VARCHAR NOT NULL,
                per_email VARCHAR NOT NULL,
                per_estado VARCHAR NOT NULL,
                tipdoc_cod INTEGER NOT NULL,
                CONSTRAINT personas_pk PRIMARY KEY (per_cod)
);

--AGREGADO REFERENCIAL DE COMPRAS
CREATE TABLE public.tipo_item (
                tipitem_cod INTEGER NOT NULL,
                tipitem_descri VARCHAR NOT NULL,
                tipitem_estado VARCHAR NOT NULL,
                CONSTRAINT tipo_item_pk PRIMARY KEY (tipitem_cod)
);

--AGREGADO REFERENCIAL DE COMPRAS
CREATE TABLE public.tipo_impuesto (
                tipimp_cod INTEGER NOT NULL,
                tipimp_descri VARCHAR NOT NULL,
                tipimp_estado VARCHAR NOT NULL,
                CONSTRAINT tipo_impuesto_pk PRIMARY KEY (tipimp_cod)
);

--AGREGADO REFERENCIAL DE COMPRAS
CREATE TABLE public.items (
                itm_cod INTEGER NOT NULL,
                tipitem_cod INTEGER NOT NULL,
                itm_descri VARCHAR NOT NULL,
                itm_costo NUMERIC NOT NULL,
                itm_precio NUMERIC NOT NULL,
                itm_estado VARCHAR NOT NULL,
                tipimp_cod INTEGER NOT NULL,
                CONSTRAINT items_pk PRIMARY KEY (itm_cod, tipitem_cod)
);

--AGREGADO REFERNCIAL DE COMPRAS
CREATE TABLE public.ciudad (
                ciu_cod INTEGER NOT NULL,
                ciu_descripcion VARCHAR NOT NULL,
                ciu_estado VARCHAR NOT NULL,
                CONSTRAINT ciudad_pk PRIMARY KEY (ciu_cod)
);

--AGREGADO REFERNCIAL DE VENTAS
CREATE TABLE public.clientes (
                cli_cod INTEGER NOT NULL,
                cli_direccion VARCHAR NOT NULL,
                cli_estado VARCHAR NOT NULL,
                per_cod INTEGER NOT NULL,
                ciu_cod INTEGER NOT NULL,
                CONSTRAINT clientes_pk PRIMARY KEY (cli_cod)
);

--AGREGADO REFERNCIAL DE COMPRAS
CREATE TABLE public.empresa (
                emp_cod INTEGER NOT NULL,
                emp_razonsocial VARCHAR NOT NULL,
                emp_ruc VARCHAR NOT NULL,
                emp_telefono VARCHAR NOT NULL,
                emp_email VARCHAR NOT NULL,
                emp_actividad VARCHAR NOT NULL,
                emp_estado VARCHAR NOT NULL,
                CONSTRAINT empresa_pk PRIMARY KEY (emp_cod)
);

--AGREGADO REFERNCIAL DE COMPRAS
CREATE TABLE public.sucursales (
                suc_cod INTEGER NOT NULL,
                emp_cod INTEGER NOT NULL,
                suc_descri VARCHAR NOT NULL,
                suc_telefono VARCHAR,
                suc_direccion VARCHAR NOT NULL,
                suc_estado VARCHAR NOT NULL,
                ciu_cod INTEGER NOT NULL,
                CONSTRAINT sucursales_pk PRIMARY KEY (suc_cod, emp_cod)
);

--AGREGADO REFERNCIAL DE SERVICIOS
CREATE TABLE public.funcionarios (
                fun_cod INTEGER NOT NULL,
                fun_fechaingreso DATE NOT NULL,
                fun_estado VARCHAR NOT NULL,
                per_cod INTEGER NOT NULL,
                ciu_cod INTEGER NOT NULL,
                car_cod INTEGER NOT NULL,
                suc_cod INTEGER NOT NULL,
                emp_cod INTEGER NOT NULL,
                CONSTRAINT funcionarios_pk PRIMARY KEY (fun_cod)
);

--AGREGADO REFERNCIAL DE SEGURIDAD
CREATE TABLE public.usuarios (
                usu_cod INTEGER NOT NULL,
                usu_login VARCHAR NOT NULL,
                usu_contrasena VARCHAR NOT NULL,
                usu_fechacrea DATE NOT NULL,
                usu_estado VARCHAR NOT NULL,
                perf_cod INTEGER NOT NULL,
                mod_cod INTEGER NOT NULL,
                fun_cod INTEGER NOT NULL,
                CONSTRAINT usuarios_pk PRIMARY KEY (usu_cod)
);

--AGREGADO REFERNCIAL DE SEGURIDAD
CREATE TABLE public.asignacion_permiso_usuarios (
                usu_cod INTEGER NOT NULL,
                perf_cod INTEGER NOT NULL,
                permi_cod INTEGER NOT NULL,
                asigusu_estado VARCHAR NOT NULL,
                CONSTRAINT asignacion_permiso_usuarios_pk PRIMARY KEY (usu_cod, perf_cod, permi_cod)
);


CREATE TABLE public.aisistencias (
                asis_cod INTEGER NOT NULL,
                asis_fecha DATE NOT NULL,
                asis_horaentrada TIME NOT NULL,
                asis_horasalida TIME NOT NULL,
                asis_estado VARCHAR NOT NULL,
                cli_cod INTEGER NOT NULL,
                usu_cod INTEGER NOT NULL,
                suc_cod INTEGER NOT NULL,
                emp_cod INTEGER NOT NULL,
                dia_cod INTEGER NOT NULL,
                CONSTRAINT aisistencias_pk PRIMARY KEY (asis_cod)
);

--AGREGADO MOVIMIENTO DE SERVICIOS
CREATE TABLE public.mediciones_cab (
                med_cod INTEGER NOT NULL,
                med_fecha DATE NOT NULL,
                med_estado VARCHAR NOT NULL,
                cli_cod INTEGER NOT NULL,
                fun_cod INTEGER NOT NULL,
                usu_cod INTEGER NOT NULL,
                suc_cod INTEGER NOT NULL,
                emp_cod INTEGER NOT NULL,
                CONSTRAINT mediciones_cab_pk PRIMARY KEY (med_cod)
);

--AGREGADO MOVIMIENTO DE SERVICIOS
CREATE TABLE public.mediciones_det (
                med_cod INTEGER NOT NULL,
                param_cod INTEGER NOT NULL,
                meddet_cantidad NUMERIC NOT NULL,
                uni_cod INTEGER NOT NULL,
                CONSTRAINT mediciones_det_pk PRIMARY KEY (med_cod, param_cod)
);

--AGREGADO MOVIMIENTO DE SERVICIOS
CREATE TABLE public.rutinas_cab (
                rut_cod INTEGER NOT NULL,
                rut_estado VARCHAR NOT NULL,
                tiprut_cod INTEGER NOT NULL,
                cli_cod INTEGER NOT NULL,
                fun_cod INTEGER NOT NULL,
                usu_cod INTEGER NOT NULL,
                suc_cod INTEGER NOT NULL,
                emp_cod INTEGER NOT NULL,
                CONSTRAINT rutinas_cab_pk PRIMARY KEY (rut_cod)
);


CREATE TABLE public.evolucion_cab (
                evo_cod INTEGER NOT NULL,
                evo_fecha DATE NOT NULL,
                evo_estado VARCHAR NOT NULL,
                cli_cod INTEGER NOT NULL,
                rut_cod INTEGER NOT NULL,
                usu_cod INTEGER NOT NULL,
                suc_cod INTEGER NOT NULL,
                emp_cod INTEGER NOT NULL,
                CONSTRAINT evolucion_cab_pk PRIMARY KEY (evo_cod)
);


CREATE TABLE public.evolucion_det (
                evo_cod INTEGER NOT NULL,
                ejer_cod INTEGER NOT NULL,
                evodet_registronterior NUMERIC NOT NULL,
                evodet_registroactual NUMERIC NOT NULL,
                uni_cod INTEGER NOT NULL,
                CONSTRAINT evolucion_det_pk PRIMARY KEY (evo_cod, ejer_cod)
);

--AGREGADO MOVIMIENTO DE SERVICIOS
CREATE TABLE public.rutinas_det (
                rut_cod INTEGER NOT NULL,
                ejer_cod INTEGER NOT NULL,
                rutdet_series INTEGER NOT NULL,
                rutdet_repeticiones INTEGER NOT NULL,
                dia_cod INTEGER NOT NULL,
                equi_cod INTEGER NOT NULL,
                tipequi_cod INTEGER NOT NULL,
                CONSTRAINT rutinas_det_pk PRIMARY KEY (rut_cod, ejer_cod)
);

--AGREGADO MOVIMIENTO DE SERVICIOS
CREATE TABLE public.plan_alimenticio_cab (
                alim_cod INTEGER NOT NULL,
                alim_estado VARCHAR NOT NULL,
                tiplan_cod INTEGER NOT NULL,
                cli_cod INTEGER NOT NULL,
                fun_cod INTEGER NOT NULL,
                usu_cod INTEGER NOT NULL,
                suc_cod INTEGER NOT NULL,
                emp_cod INTEGER NOT NULL,
                CONSTRAINT plan_alimenticio_cab_pk PRIMARY KEY (alim_cod)
);

--AGREGADO MOVIMIENTO DE SERVICIOS
CREATE TABLE public.plan_alimenticio_det (
                alim_cod INTEGER NOT NULL,
                comi_cod INTEGER NOT NULL,
                alimdet_proteina NUMERIC NOT NULL,
                alimdet_calorias NUMERIC NOT NULL,
                alimdet_carbohidratos NUMERIC NOT NULL,
                dia_cod INTEGER NOT NULL,
                hrcom_cod INTEGER NOT NULL,
                CONSTRAINT plan_alimenticio_det_pk PRIMARY KEY (alim_cod, comi_cod)
);

--AGREGADO MOVIMIENTO DE SERVICIOS
CREATE TABLE public.inscripciones_cab (
                ins_cod INTEGER NOT NULL,
                ins_fecha DATE NOT NULL,
                ins_estado VARCHAR NOT NULL,
                usu_cod INTEGER NOT NULL,
                suc_cod INTEGER NOT NULL,
                emp_cod INTEGER NOT NULL,
                cli_cod INTEGER NOT NULL,
                fun_cod INTEGER NOT NULL,
                CONSTRAINT inscripciones_cab_pk PRIMARY KEY (ins_cod)
);


CREATE TABLE public.salida (
                sal_cod INTEGER NOT NULL,
                sal_fecha DATE NOT NULL,
                sal_motivo VARCHAR NOT NULL,
                sal_estado VARCHAR NOT NULL,
                cli_cod INTEGER NOT NULL,
                ins_cod INTEGER NOT NULL,
                usu_cod INTEGER NOT NULL,
                suc_cod INTEGER NOT NULL,
                emp_cod INTEGER NOT NULL,
                CONSTRAINT salida_pk PRIMARY KEY (sal_cod)
);

--AGREGADO MOVIMIENTO DE SERVICIOS
CREATE TABLE public.presupuesto_prep_cab (
                prpr_cod INTEGER NOT NULL,
                prpr_fecha DATE NOT NULL,
                prpr_estado VARCHAR NOT NULL,
                ins_cod INTEGER NOT NULL,
                cli_cod INTEGER NOT NULL,
                usu_cod INTEGER NOT NULL,
                suc_cod INTEGER NOT NULL,
                emp_cod INTEGER NOT NULL,
                CONSTRAINT presupuesto_prep_cab_pk PRIMARY KEY (prpr_cod)
);

--AGREGADO MOVIMIENTO DE SERVICIOS
CREATE TABLE public.presupuesto_prep_det (
                prpr_cod INTEGER NOT NULL,
                itm_cod INTEGER NOT NULL,
                tipitem_cod INTEGER NOT NULL,
                prprdet_precio NUMERIC NOT NULL,
                prprdet_cantidad INTEGER NOT NULL,
                CONSTRAINT presupuesto_prep_det_pk PRIMARY KEY (prpr_cod, itm_cod, tipitem_cod)
);

--AGREGADO MOVIMIENTO DE SERVICIOS
CREATE TABLE public.inscripciones_det (
                ins_cod INTEGER NOT NULL,
                dia_cod INTEGER NOT NULL,
                insdet_horainicio TIME NOT NULL,
                insdet_horafinal TIME NOT NULL,
                CONSTRAINT inscripciones_det_pk PRIMARY KEY (ins_cod, dia_cod)
);

--AGREGADO MOVIMIENTO DE VENTAS
CREATE TABLE public.ventas_cab (
                ven_cod INTEGER NOT NULL,
                ven_fecha DATE NOT NULL,
                ven_nrofac VARCHAR NOT NULL,
                ven_tipfac VARCHAR NOT NULL,
                ven_cuotas INTEGER NOT NULL,
                ven_montocuota NUMERIC NOT NULL,
                ven_intefecha VARCHAR NOT NULL,
                ven_estado VARCHAR NOT NULL,
                cli_cod INTEGER NOT NULL,
                usu_cod INTEGER NOT NULL,
                suc_cod INTEGER NOT NULL,
                emp_cod INTEGER NOT NULL,
                CONSTRAINT ventas_cab_pk PRIMARY KEY (ven_cod)
);

--AGREGADO MOVIMIENTO DE VENTAS
CREATE TABLE public.nota_venta_cab (
                notven_cod INTEGER NOT NULL,
                notven_fecha DATE NOT NULL,
                notven_nronota VARCHAR NOT NULL,
                notven_concepto VARCHAR NOT NULL,
                notven_estado VARCHAR NOT NULL,
                tipcomp_cod INTEGER NOT NULL,
                ven_cod INTEGER NOT NULL,
                suc_cod INTEGER NOT NULL,
                emp_cod INTEGER NOT NULL,
                usu_cod INTEGER NOT NULL,
                cli_cod INTEGER NOT NULL,
                CONSTRAINT nota_venta_cab_pk PRIMARY KEY (notven_cod)
);

--AGREGADO MOVIMIENTO DE VENTAS
CREATE TABLE public.nota_venta_det (
                itm_cod INTEGER NOT NULL,
                tipitem_cod INTEGER NOT NULL,
                notven_cod INTEGER NOT NULL,
                notvendet_cantidad INTEGER NOT NULL,
                notvendet_precio NUMERIC NOT NULL,
                CONSTRAINT nota_venta_det_pk PRIMARY KEY (itm_cod, tipitem_cod, notven_cod)
);

--AGREGADO MOVIMIENTO DE VENTAS
CREATE TABLE public.libro_ventas (
                ven_cod INTEGER NOT NULL,
                libven_cod INTEGER NOT NULL,
                libven_fecha DATE NOT NULL,
                libven_numfactura VARCHAR NOT NULL,
                libven_excenta NUMERIC NOT NULL,
                libven_IVA5 NUMERIC NOT NULL,
                libven_IVA10 NUMERIC NOT NULL,
                libven_estado VARCHAR NOT NULL,
                CONSTRAINT libro_ventas_pk PRIMARY KEY (ven_cod, libven_cod)
);

--AGREGADO MOVIMIENTO DE VENTAS
CREATE TABLE public.cuentas_cobrar (
                ven_cod INTEGER NOT NULL,
                cuencob_cuotas INTEGER,
                cuencob_montotal NUMERIC NOT NULL,
                cuencob_saldo NUMERIC NOT NULL,
                cuencob_estado VARCHAR NOT NULL,
                CONSTRAINT cuentas_cobrar_pk PRIMARY KEY (ven_cod)
);

--AGREGADO MODULO DE VENTAS
CREATE TABLE public.pedido_venta_cab (
                pedven_cod INTEGER NOT NULL,
                pedven_fecha DATE NOT NULL,
                pedven_estado VARCHAR NOT NULL,
                suc_cod INTEGER NOT NULL,
                emp_cod INTEGER NOT NULL,
                usu_cod INTEGER NOT NULL,
                cli_cod INTEGER NOT NULL,
                CONSTRAINT pedido_venta_cab_pk PRIMARY KEY (pedven_cod)
);

--AGREGADO MOVIMIENTO DE VENTAS
CREATE TABLE public.venta_pedido (
                pedven_cod INTEGER NOT NULL,
                ven_cod INTEGER NOT NULL,
                venped_cod INTEGER NOT NULL,
                CONSTRAINT venta_pedido_pk PRIMARY KEY (pedven_cod, ven_cod, venped_cod)
);

--AGREGADO MODULO DE VENTAS
CREATE TABLE public.pedido_venta_det (
                itm_cod INTEGER NOT NULL,
                tipitem_cod INTEGER NOT NULL,
                pedven_cod INTEGER NOT NULL,
                pedvendet_cantidad INTEGER NOT NULL,
                pedvendet_precio NUMERIC NOT NULL,
                CONSTRAINT pedido_venta_det_pk PRIMARY KEY (itm_cod, tipitem_cod, pedven_cod)
);

--AGREGADO MODULO DE COMPRAS
CREATE TABLE public.ajuste_invenario_cab (
                ajinv_cod INTEGER NOT NULL,
                ajinv_fecha DATE NOT NULL,
                ajinv_tipoajuste VARCHAR NOT NULL,
                ajinv_estado VARCHAR NOT NULL,
                suc_cod INTEGER NOT NULL,
                emp_cod INTEGER NOT NULL,
                usu_cod INTEGER NOT NULL,
                CONSTRAINT ajuste_invenario_cab_pk PRIMARY KEY (ajinv_cod)
);

--AGREGADO REFERENCIAL DE VENTAS
CREATE TABLE public.caja (
                caj_cod INTEGER NOT NULL,
                caj_descri VARCHAR NOT NULL,
                caj_estado VARCHAR NOT NULL,
                suc_cod INTEGER NOT NULL,
                emp_cod INTEGER NOT NULL,
                CONSTRAINT caja_pk PRIMARY KEY (caj_cod)
);

--AGREGADO MODULO DE VENTAS
CREATE TABLE public.apertura_cierre (
                caj_cod INTEGER NOT NULL,
                suc_cod INTEGER NOT NULL,
                emp_cod INTEGER NOT NULL,
                usu_cod INTEGER NOT NULL,
                apcier_cod INTEGER NOT NULL,
                apcier_fechahora_aper TIMESTAMP,
                apcier_fechahora_cierre TIMESTAMP,
                apcier_monto_aper NUMERIC,
                apcier_monto_cierre NUMERIC,
                apcier_estado VARCHAR NOT NULL,
                CONSTRAINT apertura_cierre_pk PRIMARY KEY (caj_cod, suc_cod, emp_cod, usu_cod, apcier_cod)
);

--AGREGADO MODULO DE VENTAS
CREATE TABLE public.recaudaciones_depositar (
                rec_cod INTEGER NOT NULL,
                caj_cod INTEGER NOT NULL,
                suc_cod INTEGER NOT NULL,
                emp_cod INTEGER NOT NULL,
                usu_cod INTEGER NOT NULL,
                apcier_cod INTEGER NOT NULL,
                rec_montoefec NUMERIC NOT NULL,
                rec_montocheq NUMERIC NOT NULL,
                rec_estado VARCHAR NOT NULL,
                CONSTRAINT recaudaciones_depositar_pk PRIMARY KEY (caj_cod, suc_cod, emp_cod, usu_cod, apcier_cod, rec_cod)
);

--AGREGADO MODULO DE VENTAS
CREATE TABLE public.cobros_cab (
                cobr_cod INTEGER NOT NULL,
                cobr_fecha DATE NOT NULL,
                cobr_estado VARCHAR NOT NULL,
                caj_cod INTEGER NOT NULL,
                suc_cod INTEGER NOT NULL,
                emp_cod INTEGER NOT NULL,
                usu_cod INTEGER NOT NULL,
                apcier_cod INTEGER NOT NULL,
                CONSTRAINT cobros_cab_pk PRIMARY KEY (cobr_cod)
);

--AGREGADO MODULO DE VENTAS
CREATE TABLE public.cobros_det (
                ven_cod INTEGER NOT NULL,
                cobr_cod INTEGER NOT NULL,
                cobrdet_cod INTEGER NOT NULL,
                cobrdet_monto NUMERIC,
                cobrdet_nrocuota INTEGER NOT NULL,
                forcob_cod INTEGER NOT NULL,
                CONSTRAINT cobros_det_pk PRIMARY KEY (ven_cod, cobr_cod, cobrdet_cod)
);

--AGREGADO MODULO DE VENTAS
CREATE TABLE public.cobro_cheque (
                cobrcheq_cod INTEGER NOT NULL,
                cobrcheq_num VARCHAR NOT NULL,
                cobrcheq_monto NUMERIC NOT NULL,
                cobrcheq_tipcheq VARCHAR NOT NULL,
                cobrcheq_fechaven DATE NOT NULL,
                cobrcheq_estado VARCHAR NOT NULL,
                ven_cod INTEGER NOT NULL,
                cobr_cod INTEGER NOT NULL,
                cobrdet_cod INTEGER NOT NULL,
                ent_cod INTEGER NOT NULL,
                CONSTRAINT cobro_cheque_pk PRIMARY KEY (cobrcheq_cod)
);

--AGREGADO MODULO DE VENTAS
CREATE TABLE public.cobro_tarjeta (
                cobrtarj_cod INTEGER NOT NULL,
                cobrtarj_numtarj VARCHAR NOT NULL,
                cobrtarj_monto NUMERIC NOT NULL,
                cobrtarj_tiptarj VARCHAR NOT NULL,
                cobrtarj_estado VARCHAR NOT NULL,
                ven_cod INTEGER NOT NULL,
                cobr_cod INTEGER NOT NULL,
                cobrdet_cod INTEGER NOT NULL,
                martarj_cod INTEGER NOT NULL,
                ent_cod INTEGER NOT NULL,
                entahd_cod INTEGER NOT NULL,
                CONSTRAINT cobro_tarjeta_pk PRIMARY KEY (cobrtarj_cod)
);

--AGREGADO MODULO DE VENTAS
CREATE TABLE public.arqueo_control (
                caj_cod INTEGER NOT NULL,
                suc_cod INTEGER NOT NULL,
                emp_cod INTEGER NOT NULL,
                usu_cod INTEGER NOT NULL,
                apcier_cod INTEGER NOT NULL,
                arq_cod INTEGER NOT NULL,
                arq_obs VARCHAR NOT NULL,
                fun_cod INTEGER NOT NULL,
                CONSTRAINT arqueo_control_pk PRIMARY KEY (caj_cod, suc_cod, emp_cod, usu_cod, apcier_cod, arq_cod)
);

---AGREGADO MODULO DE COMPRAS
CREATE TABLE public.pedido_compra_cab (
                pedcom_cod INTEGER NOT NULL,
                pedcom_fecha DATE NOT NULL,
                pedcom_estado VARCHAR NOT NULL,
                usu_cod INTEGER NOT NULL,
                suc_cod INTEGER NOT NULL,
                emp_cod INTEGER NOT NULL,
                CONSTRAINT pedido_compra_cab_pk PRIMARY KEY (pedcom_cod)
);

---AGREGADO MODULO DE COMPRAS
CREATE TABLE public.pedido_compra_det (
                itm_cod INTEGER NOT NULL,
                tipitem_cod INTEGER NOT NULL,
                pedcom_cod INTEGER NOT NULL,
                pedcomdet_cantidad INTEGER NOT NULL,
                pedcomdet_precio NUMERIC NOT NULL,
                CONSTRAINT pedido_compra_det_pk PRIMARY KEY (itm_cod, tipitem_cod, pedcom_cod)
);

---AGREGADO REFERENCIAL DE COMPRAS
CREATE TABLE public.depositos (
                dep_cod INTEGER NOT NULL,
                suc_cod INTEGER NOT NULL,
                emp_cod INTEGER NOT NULL,
                dep_descripcion VARCHAR NOT NULL,
                dep_estado VARCHAR NOT NULL,
                ciu_cod INTEGER NOT NULL,
                CONSTRAINT depositos_pk PRIMARY KEY (dep_cod, suc_cod, emp_cod)
);

---AGREGADO REFERENCIAL DE COMPRAS
CREATE TABLE stock (
                itm_cod INTEGER NOT NULL,
                tipitem_cod INTEGER NOT NULL,
                dep_cod INTEGER NOT NULL,
                suc_cod INTEGER NOT NULL,
                emp_cod INTEGER NOT NULL,
                sto_cantidad INTEGER NOT NULL,
                uni_cod INTEGER not null,
                CONSTRAINT stock_pk PRIMARY KEY (itm_cod, tipitem_cod, dep_cod, suc_cod, emp_cod)
);

--AGREGADO MOVIMIENTO DE VENTAS
CREATE TABLE public.ventas_det (
                ven_cod INTEGER NOT NULL,
                itm_cod INTEGER NOT NULL,
                tipitem_cod INTEGER NOT NULL,
                dep_cod INTEGER NOT NULL,
                suc_cod INTEGER NOT NULL,
                emp_cod INTEGER NOT NULL,
                vendet_cantidad INTEGER NOT NULL,
                vendet_precio NUMERIC NOT NULL,
                CONSTRAINT ventas_det_pk PRIMARY KEY (ven_cod, itm_cod, tipitem_cod, dep_cod, suc_cod, emp_cod)
);

--AGREGADO MODULO DE COMPRAS
CREATE TABLE public.ajuste_invenario_det (
                itm_cod INTEGER NOT NULL,
                tipitem_cod INTEGER NOT NULL,
                dep_cod INTEGER NOT NULL,
                suc_cod INTEGER NOT NULL,
                emp_cod INTEGER NOT NULL,
                ajinv_cod INTEGER NOT NULL,
                ajinvdet_motivo VARCHAR NOT NULL,
                ajinvdet_cantidad INTEGER NOT NULL,
                CONSTRAINT ajuste_invenario_det_pk PRIMARY KEY (itm_cod, tipitem_cod, dep_cod, suc_cod, emp_cod, ajinv_cod)
);

--AGREGADO REFERENCIAL DE COMPRAS
CREATE TABLE public.tipo_proveedor (
                tiprov_cod INTEGER NOT NULL,
                tiprov_descripcion VARCHAR NOT NULL,
                tiprov_estado VARCHAR NOT NULL,
                CONSTRAINT tipo_proveedor_pk PRIMARY KEY (tiprov_cod)
);

--AGREGADO REFERENCIAL DE COMPRAS
CREATE TABLE public.proveedor (
                pro_cod INTEGER NOT NULL,
                tiprov_cod INTEGER NOT NULL,
                pro_razonsocial VARCHAR NOT NULL,
                pro_ruc VARCHAR NOT NULL,
                pro_direccion VARCHAR NOT NULL,
                pro_telefono VARCHAR NOT NULL,
                pro_email VARCHAR,
                pro_estado VARCHAR NOT NULL,
                CONSTRAINT proveedor_pk PRIMARY KEY (pro_cod, tiprov_cod)
);

--AGREGADO MODULO DE COMPRAS
CREATE TABLE public.compra_cab (
                com_cod INTEGER NOT NULL,
                com_fecha DATE NOT NULL,
                com_nrofac VARCHAR NOT NULL,
                com_tipfac VARCHAR NOT NULL,
                com_cuotas VARCHAR,
                com_intefecha VARCHAR,
                com_estado VARCHAR NOT NULL,
                pro_cod INTEGER NOT NULL,
                tiprov_cod INTEGER NOT NULL,
                usu_cod INTEGER NOT NULL,
                suc_cod INTEGER NOT NULL,
                emp_cod INTEGER NOT NULL,
                CONSTRAINT compra_cab_pk PRIMARY KEY (com_cod)
);

--AGREGADO MODULO DE COMPRAS
CREATE TABLE public.nota_compra_cab (
                notacom_cod INTEGER NOT NULL,
                notacom_fecha DATE NOT NULL,
                notacom_nronota VARCHAR NOT NULL,
                notacom_concepto VARCHAR NOT NULL,
                notacom_estado VARCHAR NOT NULL,
                com_cod INTEGER NOT NULL,
                suc_cod INTEGER NOT NULL,
                emp_cod INTEGER NOT NULL,
                usu_cod INTEGER NOT NULL,
                tipcomp_cod INTEGER NOT NULL,
                pro_cod INTEGER NOT NULL,
                tiprov_cod INTEGER NOT NULL,
                CONSTRAINT nota_compra_cab_pk PRIMARY KEY (notacom_cod)
);

--AGREGADO MODULO DE COMPRAS
CREATE TABLE public.nota_compra_det (
                itm_cod INTEGER NOT NULL,
                tipitem_cod INTEGER NOT NULL,
                notacom_cod INTEGER NOT NULL,
                notacomdet_cantidad INTEGER NOT NULL,
                notacomdet_precio NUMERIC NOT NULL,
                CONSTRAINT nota_compra_det_pk PRIMARY KEY (itm_cod, tipitem_cod, notacom_cod)
);

--AGREGADO MODULO DE COMPRAS
CREATE TABLE public.libro_compras (
                com_cod INTEGER NOT NULL,
                libcom_cod INTEGER NOT NULL,
                libcom_fecha DATE NOT NULL,
                libcom_numfactura VARCHAR NOT NULL,
                libcom_excenta NUMERIC,
                libcom_IVA5 NUMERIC,
                libcom_IVA10 NUMERIC,
                libcom_estado VARCHAR NOT NULL,
                CONSTRAINT libro_compras_pk PRIMARY KEY (com_cod, libcom_cod)
);

--AGREGADO MODULO DE COMPRAS
CREATE TABLE public.cuentas_pagar (
                com_cod INTEGER NOT NULL,
                cuenpag_cuotas INTEGER,
                cuenpag_montotal NUMERIC NOT NULL,
                cuenpag_saldo NUMERIC NOT NULL,
                cuenpag_estado VARCHAR NOT NULL,
                CONSTRAINT cuentas_pagar_pk PRIMARY KEY (com_cod)
);

--AGREGADO REFERENCIAL DE COMPRAS
CREATE TABLE public.compra_det (
                itm_cod INTEGER NOT NULL,
                tipitem_cod INTEGER NOT NULL,
                dep_cod INTEGER NOT NULL,
                suc_cod INTEGER NOT NULL,
                emp_cod INTEGER NOT NULL,
                com_cod INTEGER NOT NULL,
                comdet_cantidad NUMERIC NOT NULL,
                comdet_precio VARCHAR NOT NULL,
                CONSTRAINT compra_det_pk PRIMARY KEY (itm_cod, tipitem_cod, dep_cod, suc_cod, emp_cod, com_cod)
);

--AGREGADO MODULO DE COMPRAS
CREATE TABLE public.orden_compra_cab (
                ordcom_cod INTEGER NOT NULL,
                ordcom_fecha DATE NOT NULL,
                ordcom_condicionpago VARCHAR NOT NULL,
                ordcom_cuota INTEGER,
                ordcom_intefecha VARCHAR,
                ordcom_estado VARCHAR NOT NULL,
                pro_cod INTEGER NOT NULL,
                tiprov_cod INTEGER NOT NULL,
                suc_cod INTEGER NOT NULL,
                emp_cod INTEGER NOT NULL,
                usu_cod INTEGER NOT NULL,
                CONSTRAINT orden_compra_cab_pk PRIMARY KEY (ordcom_cod)
);

--AGREGADO MODULO DE COMPRAS
CREATE TABLE public.compra_orden (
                ordcom_cod INTEGER NOT NULL,
                com_cod INTEGER NOT NULL,
                comor_cod INTEGER NOT NULL,
                CONSTRAINT compra_orden_pk PRIMARY KEY (ordcom_cod, com_cod, comor_cod)
);

--AGREGADO MODULO DE COMPRAS
CREATE TABLE public.orden_compra_det (
                itm_cod INTEGER NOT NULL,
                tipitem_cod INTEGER NOT NULL,
                ordcom_cod INTEGER NOT NULL,
                ordcomdet_cantidad NUMERIC NOT NULL,
                ordcomdet_precio NUMERIC NOT NULL,
                CONSTRAINT orden_compra_det_pk PRIMARY KEY (itm_cod, tipitem_cod, ordcom_cod)
);

--AGREGADO MODULO DE COMPRAS
CREATE TABLE public.presupuesto_prov_cab (
                presprov_cod INTEGER NOT NULL,
                presprov_fecha DATE NOT NULL,
                presprov_fechavenci DATE NOT NULL,
                presprov_estado VARCHAR NOT NULL,
                pro_cod INTEGER NOT NULL,
                tiprov_cod INTEGER NOT NULL,
                suc_cod INTEGER NOT NULL,
                emp_cod INTEGER NOT NULL,
                usu_cod INTEGER NOT NULL,
                CONSTRAINT presupuesto_prov_cab_pk PRIMARY KEY (presprov_cod)
);

--AGREGADO MODULO DE COMPRAS
CREATE TABLE public.presupuesto_orden (
                presprov_cod INTEGER NOT NULL,
                ordcom_cod INTEGER NOT NULL,
                preor_cod INTEGER NOT NULL,
                CONSTRAINT presupuesto_orden_pk PRIMARY KEY (presprov_cod, ordcom_cod, preor_cod)
);

--AGREGADO MODULO DE COMPRAS
CREATE TABLE public.pedido_presupuesto (
                pedcom_cod INTEGER NOT NULL,
                presprov_cod INTEGER NOT NULL,
                pedpre_cod INTEGER NOT NULL,
                CONSTRAINT pedido_presupuesto_pk PRIMARY KEY (pedcom_cod, presprov_cod, pedpre_cod)
);

--AGREGADO MODULO DE COMPRAS
CREATE TABLE public.presupuesto_prov_det (
                itm_cod INTEGER NOT NULL,
                tipitem_cod INTEGER NOT NULL,
                presprov_cod INTEGER NOT NULL,
                presprovdet_cantidad NUMERIC NOT NULL,
                presprovdet_precio NUMERIC NOT NULL,
                CONSTRAINT presupuesto_prov_det_pk PRIMARY KEY (itm_cod, tipitem_cod, presprov_cod)
);

--AGREGADO
ALTER TABLE public.plan_alimenticio_det ADD CONSTRAINT horarios_comida_plan_alimenticio_det_fk
FOREIGN KEY (hrcom_cod)
REFERENCES public.horarios_comida (hrcom_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.mediciones_det ADD CONSTRAINT unidad_medida_mediciones_det_fk
FOREIGN KEY (uni_cod)
REFERENCES public.unidad_medida (uni_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE public.evolucion_det ADD CONSTRAINT unidad_medida_evolucion_det_fk
FOREIGN KEY (uni_cod)
REFERENCES public.unidad_medida (uni_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.mediciones_det ADD CONSTRAINT parametros_medicion_mediciones_det_fk
FOREIGN KEY (param_cod)
REFERENCES public.parametros_medicion (param_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.plan_alimenticio_det ADD CONSTRAINT comidas_plan_alimenticio_det_fk
FOREIGN KEY (comi_cod)
REFERENCES public.comidas (comi_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO 
ALTER TABLE public.GUI ADD CONSTRAINT modulos_gui_fk
FOREIGN KEY (mod_cod)
REFERENCES public.modulos (mod_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.usuarios ADD CONSTRAINT modulos_usuarios_fk
FOREIGN KEY (mod_cod)
REFERENCES public.modulos (mod_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.gui_perfiles ADD CONSTRAINT gui_gui_perfiles_fk
FOREIGN KEY (gui_cod, mod_cod)
REFERENCES public.GUI (gui_cod, mod_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.perfiles_permisos ADD CONSTRAINT permisos_perfiles_permisos_fk
FOREIGN KEY (permi_cod)
REFERENCES public.permisos (permi_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.usuarios ADD CONSTRAINT perfiles_usuarios_fk
FOREIGN KEY (perf_cod)
REFERENCES public.perfiles (perf_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.perfiles_permisos ADD CONSTRAINT perfiles_perfiles_permisos_fk
FOREIGN KEY (perf_cod)
REFERENCES public.perfiles (perf_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE public.gui_perfiles ADD CONSTRAINT perfiles_gui_perfiles_fk
FOREIGN KEY (perf_cod)
REFERENCES public.perfiles (perf_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.asignacion_permiso_usuarios ADD CONSTRAINT perfiles_permisos_asignacion_permiso_usuarios_fk
FOREIGN KEY (perf_cod, permi_cod)
REFERENCES public.perfiles_permisos (perf_cod, permi_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.rutinas_cab ADD CONSTRAINT tipo_rutinas_rutinas_cab_fk
FOREIGN KEY (tiprut_cod)
REFERENCES public.tipo_rutinas (tiprut_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.plan_alimenticio_cab ADD CONSTRAINT tipo_plan_alimenticio_plan_alimenticio_cab_fk
FOREIGN KEY (tiplan_cod)
REFERENCES public.tipo_plan_alimenticio (tiplan_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.rutinas_det ADD CONSTRAINT ejercicios_rutinas_det_fk
FOREIGN KEY (ejer_cod)
REFERENCES public.ejercicios (ejer_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE public.evolucion_det ADD CONSTRAINT ejercicios_evolucion_det_fk
FOREIGN KEY (ejer_cod)
REFERENCES public.ejercicios (ejer_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.inscripciones_det ADD CONSTRAINT dias_inscripciones_det_fk
FOREIGN KEY (dia_cod)
REFERENCES public.dias (dia_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.plan_alimenticio_det ADD CONSTRAINT dias_plan_alimenticio_det_fk
FOREIGN KEY (dia_cod)
REFERENCES public.dias (dia_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.rutinas_det ADD CONSTRAINT dias_rutinas_det_fk
FOREIGN KEY (dia_cod)
REFERENCES public.dias (dia_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE public.aisistencias ADD CONSTRAINT dias_aisistencias_cab_fk
FOREIGN KEY (dia_cod)
REFERENCES public.dias (dia_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.equipos ADD CONSTRAINT tipo_equipos_equipos_fk
FOREIGN KEY (tipequi_cod)
REFERENCES public.tipo_equipos (tipequi_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.rutinas_det ADD CONSTRAINT equipos_rutinas_det_fk
FOREIGN KEY (equi_cod, tipequi_cod)
REFERENCES public.equipos (equi_cod, tipequi_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.funcionarios ADD CONSTRAINT cargos_funcionarios_fk
FOREIGN KEY (car_cod)
REFERENCES public.cargos (car_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.entidad_adherida ADD CONSTRAINT marca_tarjeta_entidad_adherida_fk
FOREIGN KEY (martarj_cod)
REFERENCES public.marca_tarjeta (martarj_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO MODULO DE VENTAS
ALTER TABLE public.cobros_det ADD CONSTRAINT forma_cobro_cobros_det_fk
FOREIGN KEY (forcob_cod)
REFERENCES public.forma_cobro (forcob_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.entidad_adherida ADD CONSTRAINT entidad_emisora_entidad_adherida_fk
FOREIGN KEY (ent_cod)
REFERENCES public.entidad_emisora (ent_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.presupuesto_venta ADD CONSTRAINT preuspuesto_preparacion_cab_presupuesto_venta_fk
FOREIGN KEY (prpr_cod)
REFERENCES public.presupuesto_prep_cab (prpr_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE public.presupuesto_venta ADD CONSTRAINT ventas_cab_presupuesto_venta_fk
FOREIGN KEY (ven_cod)
REFERENCES public.ventas_cab (ven_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO MODULO DE VENTAS
ALTER TABLE public.cobro_cheque ADD CONSTRAINT entidad_emisora_cobro_cheque_fk
FOREIGN KEY (ent_cod)
REFERENCES public.entidad_emisora (ent_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO MODULO DE VENTAS
ALTER TABLE public.cobro_tarjeta ADD CONSTRAINT entidad_adherida_cobro_tarjeta_fk
FOREIGN KEY (martarj_cod, ent_cod, entahd_cod)
REFERENCES public.entidad_adherida (martarj_cod, ent_cod, entahd_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

-- AGREGADO
ALTER TABLE public.nota_compra_cab ADD CONSTRAINT tipo_comprobante_nota_compra_cab_fk
FOREIGN KEY (tipcomp_cod)
REFERENCES public.tipo_comprobante (tipcomp_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

-- AGREGADO
ALTER TABLE public.nota_venta_cab ADD CONSTRAINT tipo_comprobante_nota_venta_cab_fk
FOREIGN KEY (tipcomp_cod)
REFERENCES public.tipo_comprobante (tipcomp_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

-- AGREGADO
ALTER TABLE public.personas ADD CONSTRAINT tipo_documento_personas_fk
FOREIGN KEY (tipdoc_cod)
REFERENCES public.tipo_documento (tipdoc_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

-- AGREGADO
ALTER TABLE public.clientes ADD CONSTRAINT personas_clientes_fk
FOREIGN KEY (per_cod)
REFERENCES public.personas (per_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.funcionarios ADD CONSTRAINT personas_funcionarios_fk
FOREIGN KEY (per_cod)
REFERENCES public.personas (per_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.items ADD CONSTRAINT tipo_item_items_fk
FOREIGN KEY (tipitem_cod)
REFERENCES public.tipo_item (tipitem_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.items ADD CONSTRAINT tipo_impuesto_items_fk
FOREIGN KEY (tipimp_cod)
REFERENCES public.tipo_impuesto (tipimp_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.pedido_compra_det ADD CONSTRAINT items_pedido_compra_det_fk
FOREIGN KEY (itm_cod, tipitem_cod)
REFERENCES public.items (itm_cod, tipitem_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.presupuesto_prov_det ADD CONSTRAINT items_presupuesto_prov_det_fk
FOREIGN KEY (itm_cod, tipitem_cod)
REFERENCES public.items (itm_cod, tipitem_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.orden_compra_det ADD CONSTRAINT items_orden_compra_det_fk
FOREIGN KEY (itm_cod, tipitem_cod)
REFERENCES public.items (itm_cod, tipitem_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.stock ADD CONSTRAINT items_stock_fk
FOREIGN KEY (itm_cod, tipitem_cod)
REFERENCES public.items (itm_cod, tipitem_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.nota_compra_det ADD CONSTRAINT items_nota_compra_det_fk
FOREIGN KEY (itm_cod, tipitem_cod)
REFERENCES public.items (itm_cod, tipitem_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.pedido_venta_det ADD CONSTRAINT items_pedido_venta_det_fk
FOREIGN KEY (itm_cod, tipitem_cod)
REFERENCES public.items (itm_cod, tipitem_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.nota_venta_det ADD CONSTRAINT items_nota_venta_det_fk
FOREIGN KEY (itm_cod, tipitem_cod)
REFERENCES public.items (itm_cod, tipitem_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.presupuesto_prep_det ADD CONSTRAINT items_presupuesto_prep_det_fk
FOREIGN KEY (itm_cod, tipitem_cod)
REFERENCES public.items (itm_cod, tipitem_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.sucursales ADD CONSTRAINT ciudad_sucursal_fk
FOREIGN KEY (ciu_cod)
REFERENCES public.ciudad (ciu_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.depositos ADD CONSTRAINT ciudad_depositos_fk
FOREIGN KEY (ciu_cod)
REFERENCES public.ciudad (ciu_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.funcionarios ADD CONSTRAINT ciudad_funcionarios_fk
FOREIGN KEY (ciu_cod)
REFERENCES public.ciudad (ciu_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.clientes ADD CONSTRAINT ciudad_clientes_fk
FOREIGN KEY (ciu_cod)
REFERENCES public.ciudad (ciu_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.pedido_venta_cab ADD CONSTRAINT clientes_pedido_venta_cab_fk
FOREIGN KEY (cli_cod)
REFERENCES public.clientes (cli_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.ventas_cab ADD CONSTRAINT clientes_ventas_cab_fk
FOREIGN KEY (cli_cod)
REFERENCES public.clientes (cli_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

-- AGREGADO
ALTER TABLE public.nota_venta_cab ADD CONSTRAINT clientes_nota_venta_cab_fk
FOREIGN KEY (cli_cod)
REFERENCES public.clientes (cli_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.inscripciones_cab ADD CONSTRAINT clientes_inscripciones_cab_fk
FOREIGN KEY (cli_cod)
REFERENCES public.clientes (cli_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.plan_alimenticio_cab ADD CONSTRAINT clientes_plan_alimenticio_cab_fk
FOREIGN KEY (cli_cod)
REFERENCES public.clientes (cli_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.rutinas_cab ADD CONSTRAINT clientes_rutinas_cab_fk
FOREIGN KEY (cli_cod)
REFERENCES public.clientes (cli_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.mediciones_cab ADD CONSTRAINT clientes_mediciones_cab_fk
FOREIGN KEY (cli_cod)
REFERENCES public.clientes (cli_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.presupuesto_prep_cab ADD CONSTRAINT clientes_presupuesto_prep_cab_fk
FOREIGN KEY (cli_cod)
REFERENCES public.clientes (cli_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE public.evolucion_cab ADD CONSTRAINT clientes_evolucion_cab_fk
FOREIGN KEY (cli_cod)
REFERENCES public.clientes (cli_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE public.aisistencias ADD CONSTRAINT clientes_aisistencias_cab_fk
FOREIGN KEY (cli_cod)
REFERENCES public.clientes (cli_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE public.salida ADD CONSTRAINT clientes_salida_fk
FOREIGN KEY (cli_cod)
REFERENCES public.clientes (cli_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.sucursales ADD CONSTRAINT empresa_sucursal_fk
FOREIGN KEY (emp_cod)
REFERENCES public.empresa (emp_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.depositos ADD CONSTRAINT sucursal_depositos_fk
FOREIGN KEY (suc_cod, emp_cod)
REFERENCES public.sucursales (suc_cod, emp_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.pedido_compra_cab ADD CONSTRAINT sucursal_pedido_compra_cab_fk
FOREIGN KEY (suc_cod, emp_cod)
REFERENCES public.sucursales (suc_cod, emp_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.caja ADD CONSTRAINT sucursal_caja_fk
FOREIGN KEY (suc_cod, emp_cod)
REFERENCES public.sucursales (suc_cod, emp_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.presupuesto_prov_cab ADD CONSTRAINT sucursal_presupuesto_prov_cab_fk
FOREIGN KEY (suc_cod, emp_cod)
REFERENCES public.sucursales (suc_cod, emp_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.orden_compra_cab ADD CONSTRAINT sucursal_orden_compra_cab_fk
FOREIGN KEY (suc_cod, emp_cod)
REFERENCES public.sucursales (suc_cod, emp_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.compra_cab ADD CONSTRAINT sucursal_compra_cab_fk
FOREIGN KEY (suc_cod, emp_cod)
REFERENCES public.sucursales (suc_cod, emp_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.ajuste_invenario_cab ADD CONSTRAINT sucursal_ajuste_invenario_cab_fk
FOREIGN KEY (suc_cod, emp_cod)
REFERENCES public.sucursales (suc_cod, emp_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

-- AGREGADO
ALTER TABLE public.nota_compra_cab ADD CONSTRAINT sucursal_nota_compra_cab_fk
FOREIGN KEY (suc_cod, emp_cod)
REFERENCES public.sucursales (suc_cod, emp_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.pedido_venta_cab ADD CONSTRAINT sucursal_pedido_venta_cab_fk
FOREIGN KEY (suc_cod, emp_cod)
REFERENCES public.sucursales (suc_cod, emp_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.ventas_cab ADD CONSTRAINT sucursal_ventas_cab_fk
FOREIGN KEY (suc_cod, emp_cod)
REFERENCES public.sucursales (suc_cod, emp_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.apertura_cierre ADD CONSTRAINT sucursal_apertura_cierre_fk
FOREIGN KEY (suc_cod, emp_cod)
REFERENCES public.sucursales (suc_cod, emp_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

-- AGREGADO
ALTER TABLE public.nota_venta_cab ADD CONSTRAINT sucursal_nota_venta_cab_fk
FOREIGN KEY (suc_cod, emp_cod)
REFERENCES public.sucursales (suc_cod, emp_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.inscripciones_cab ADD CONSTRAINT sucursales_inscripciones_cab_fk
FOREIGN KEY (suc_cod, emp_cod)
REFERENCES public.sucursales (suc_cod, emp_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.plan_alimenticio_cab ADD CONSTRAINT sucursales_plan_alimenticio_cab_fk
FOREIGN KEY (suc_cod, emp_cod)
REFERENCES public.sucursales (suc_cod, emp_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.rutinas_cab ADD CONSTRAINT sucursales_rutinas_cab_fk
FOREIGN KEY (suc_cod, emp_cod)
REFERENCES public.sucursales (suc_cod, emp_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.mediciones_cab ADD CONSTRAINT sucursales_mediciones_cab_fk
FOREIGN KEY (suc_cod, emp_cod)
REFERENCES public.sucursales (suc_cod, emp_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.presupuesto_prep_cab ADD CONSTRAINT sucursales_presupuesto_prep_cab_fk
FOREIGN KEY (suc_cod, emp_cod)
REFERENCES public.sucursales (suc_cod, emp_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE public.evolucion_cab ADD CONSTRAINT sucursales_evolucion_cab_fk
FOREIGN KEY (suc_cod, emp_cod)
REFERENCES public.sucursales (suc_cod, emp_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE public.aisistencias ADD CONSTRAINT sucursales_aisistencias_cab_fk
FOREIGN KEY (suc_cod, emp_cod)
REFERENCES public.sucursales (suc_cod, emp_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE public.salida ADD CONSTRAINT sucursales_salida_fk
FOREIGN KEY (suc_cod, emp_cod)
REFERENCES public.sucursales (suc_cod, emp_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.funcionarios ADD CONSTRAINT funcionarios_sucursales_fk
FOREIGN KEY (suc_cod, emp_cod)
REFERENCES public.sucursales (suc_cod, emp_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.usuarios ADD CONSTRAINT funcionarios_usuarios_fk
FOREIGN KEY (fun_cod)
REFERENCES public.funcionarios (fun_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.arqueo_control ADD CONSTRAINT funcionarios_arqueo_control_fk
FOREIGN KEY (fun_cod)
REFERENCES public.funcionarios (fun_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.inscripciones_cab ADD CONSTRAINT funcionarios_inscripciones_cab_fk
FOREIGN KEY (fun_cod)
REFERENCES public.funcionarios (fun_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.plan_alimenticio_cab ADD CONSTRAINT funcionarios_plan_alimenticio_cab_fk
FOREIGN KEY (fun_cod)
REFERENCES public.funcionarios (fun_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.rutinas_cab ADD CONSTRAINT funcionarios_rutinas_cab_fk
FOREIGN KEY (fun_cod)
REFERENCES public.funcionarios (fun_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.mediciones_cab ADD CONSTRAINT funcionarios_mediciones_cab_fk
FOREIGN KEY (fun_cod)
REFERENCES public.funcionarios (fun_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.pedido_compra_cab ADD CONSTRAINT usuarios_pedido_compra_cab_fk
FOREIGN KEY (usu_cod)
REFERENCES public.usuarios (usu_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.presupuesto_prov_cab ADD CONSTRAINT usuarios_presupuesto_prov_cab_fk
FOREIGN KEY (usu_cod)
REFERENCES public.usuarios (usu_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.orden_compra_cab ADD CONSTRAINT usuarios_orden_compra_cab_fk
FOREIGN KEY (usu_cod)
REFERENCES public.usuarios (usu_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.compra_cab ADD CONSTRAINT usuarios_compra_cab_fk
FOREIGN KEY (usu_cod)
REFERENCES public.usuarios (usu_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.ajuste_invenario_cab ADD CONSTRAINT usuarios_ajuste_invenario_cab_fk
FOREIGN KEY (usu_cod)
REFERENCES public.usuarios (usu_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.nota_compra_cab ADD CONSTRAINT usuarios_nota_compra_cab_fk
FOREIGN KEY (usu_cod)
REFERENCES public.usuarios (usu_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.pedido_venta_cab ADD CONSTRAINT usuarios_pedido_venta_cab_fk
FOREIGN KEY (usu_cod)
REFERENCES public.usuarios (usu_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.ventas_cab ADD CONSTRAINT usuarios_ventas_cab_fk
FOREIGN KEY (usu_cod)
REFERENCES public.usuarios (usu_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.apertura_cierre ADD CONSTRAINT usuarios_apertura_cierre_fk
FOREIGN KEY (usu_cod)
REFERENCES public.usuarios (usu_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

-- AGREGADO
ALTER TABLE public.nota_venta_cab ADD CONSTRAINT usuarios_nota_venta_cab_fk
FOREIGN KEY (usu_cod)
REFERENCES public.usuarios (usu_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.inscripciones_cab ADD CONSTRAINT usuarios_inscripciones_cab_fk
FOREIGN KEY (usu_cod)
REFERENCES public.usuarios (usu_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.plan_alimenticio_cab ADD CONSTRAINT usuarios_plan_alimenticio_cab_fk
FOREIGN KEY (usu_cod)
REFERENCES public.usuarios (usu_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.rutinas_cab ADD CONSTRAINT usuarios_rutinas_cab_fk
FOREIGN KEY (usu_cod)
REFERENCES public.usuarios (usu_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.mediciones_cab ADD CONSTRAINT usuarios_mediciones_cab_fk
FOREIGN KEY (usu_cod)
REFERENCES public.usuarios (usu_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.presupuesto_prep_cab ADD CONSTRAINT usuarios_presupuesto_prep_cab_fk
FOREIGN KEY (usu_cod)
REFERENCES public.usuarios (usu_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE public.evolucion_cab ADD CONSTRAINT usuarios_evolucion_cab_fk
FOREIGN KEY (usu_cod)
REFERENCES public.usuarios (usu_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE public.aisistencias ADD CONSTRAINT usuarios_aisistencias_cab_fk
FOREIGN KEY (usu_cod)
REFERENCES public.usuarios (usu_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE public.salida ADD CONSTRAINT usuarios_salida_fk
FOREIGN KEY (usu_cod)
REFERENCES public.usuarios (usu_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.asignacion_permiso_usuarios ADD CONSTRAINT usuarios_asignacion_permiso_usuarios_fk
FOREIGN KEY (usu_cod)
REFERENCES public.usuarios (usu_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.mediciones_det ADD CONSTRAINT mediciones_cab_mediciones_det_fk
FOREIGN KEY (med_cod)
REFERENCES public.mediciones_cab (med_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.rutinas_det ADD CONSTRAINT rutinas_cab_rutinas_det_fk
FOREIGN KEY (rut_cod)
REFERENCES public.rutinas_cab (rut_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE public.evolucion_cab ADD CONSTRAINT rutinas_cab_evolucion_cab_fk
FOREIGN KEY (rut_cod)
REFERENCES public.rutinas_cab (rut_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE public.evolucion_det ADD CONSTRAINT evolucion_cab_evolucion_det_fk
FOREIGN KEY (evo_cod)
REFERENCES public.evolucion_cab (evo_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.plan_alimenticio_det ADD CONSTRAINT plan_alimenticio_cab_plan_alimenticio_det_fk
FOREIGN KEY (alim_cod)
REFERENCES public.plan_alimenticio_cab (alim_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.inscripciones_det ADD CONSTRAINT inscripciones_cab_inscripciones_det_fk
FOREIGN KEY (ins_cod)
REFERENCES public.inscripciones_cab (ins_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.presupuesto_prep_cab ADD CONSTRAINT inscripciones_cab_presupuesto_prep_cab_fk
FOREIGN KEY (ins_cod)
REFERENCES public.inscripciones_cab (ins_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE public.salida ADD CONSTRAINT inscripciones_cab_salida_fk
FOREIGN KEY (ins_cod)
REFERENCES public.inscripciones_cab (ins_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE public.presupuesto_prep_det ADD CONSTRAINT presupuesto_prep_cab_presupuesto_prep_det_fk
FOREIGN KEY (prpr_cod)
REFERENCES public.presupuesto_prep_cab (prpr_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.ventas_det ADD CONSTRAINT ventas_cab_ventas_det_fk
FOREIGN KEY (ven_cod)
REFERENCES public.ventas_cab (ven_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.venta_pedido ADD CONSTRAINT ventas_cab_pedido_venta_fk
FOREIGN KEY (ven_cod)
REFERENCES public.ventas_cab (ven_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.cuentas_cobrar ADD CONSTRAINT ventas_cab_cuentas_cobrar_fk
FOREIGN KEY (ven_cod)
REFERENCES public.ventas_cab (ven_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.libro_ventas ADD CONSTRAINT ventas_cab_libro_ventas_fk
FOREIGN KEY (ven_cod)
REFERENCES public.ventas_cab (ven_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

-- AGREGADOr
ALTER TABLE public.nota_venta_cab ADD CONSTRAINT ventas_cab_nota_venta_cab_fk
FOREIGN KEY (ven_cod)
REFERENCES public.ventas_cab (ven_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

-- AGREGADO
ALTER TABLE public.nota_venta_det ADD CONSTRAINT nota_venta_cab_nota_venta_det_fk
FOREIGN KEY (notven_cod)
REFERENCES public.nota_venta_cab (notven_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO MODULO DE VENTAS
ALTER TABLE public.cobros_det ADD CONSTRAINT cuentas_cobrar_cobros_det_fk
FOREIGN KEY (ven_cod)
REFERENCES public.cuentas_cobrar (ven_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.pedido_venta_det ADD CONSTRAINT pedido_venta_cab_pedido_venta_det_fk
FOREIGN KEY (pedven_cod)
REFERENCES public.pedido_venta_cab (pedven_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.venta_pedido ADD CONSTRAINT pedido_venta_cab_pedido_venta_fk
FOREIGN KEY (pedven_cod)
REFERENCES public.pedido_venta_cab (pedven_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.ajuste_invenario_det ADD CONSTRAINT ajuste_invenario_cab_ajuste_invenario_det_fk
FOREIGN KEY (ajinv_cod)
REFERENCES public.ajuste_invenario_cab (ajinv_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.apertura_cierre ADD CONSTRAINT caja_apertura_cierre_fk
FOREIGN KEY (caj_cod)
REFERENCES public.caja (caj_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.arqueo_control ADD CONSTRAINT apertura_cierre_arqueo_control_fk
FOREIGN KEY (caj_cod, suc_cod, emp_cod, usu_cod, apcier_cod)
REFERENCES public.apertura_cierre (caj_cod, suc_cod, emp_cod, usu_cod, apcier_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO 
ALTER TABLE public.cobros_cab ADD CONSTRAINT apertura_cierre_cobros_cab_fk
FOREIGN KEY (caj_cod, suc_cod, emp_cod, usu_cod, apcier_cod)
REFERENCES public.apertura_cierre (caj_cod, suc_cod, emp_cod, usu_cod, apcier_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO 
ALTER TABLE public.recaudaciones_depositar ADD CONSTRAINT apertura_cierre_recaudaciones_depositar_fk
FOREIGN KEY (caj_cod, suc_cod, emp_cod, usu_cod, apcier_cod)
REFERENCES public.apertura_cierre (caj_cod, suc_cod, emp_cod, usu_cod, apcier_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO MODULO DE VENTAS
ALTER TABLE public.cobros_det ADD CONSTRAINT cobros_cab_cobros_det_fk
FOREIGN KEY (cobr_cod)
REFERENCES public.cobros_cab (cobr_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO MODULO DE VENTAS
ALTER TABLE public.cobro_tarjeta ADD CONSTRAINT cobros_det_cobro_tarjeta_fk
FOREIGN KEY (ven_cod, cobr_cod, cobrdet_cod)
REFERENCES public.cobros_det (ven_cod, cobr_cod, cobrdet_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO MODULO DE VENTAS
ALTER TABLE public.cobro_cheque ADD CONSTRAINT cobros_det_cobro_cheque_fk
FOREIGN KEY (ven_cod, cobr_cod, cobrdet_cod)
REFERENCES public.cobros_det (ven_cod, cobr_cod, cobrdet_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.pedido_compra_det ADD CONSTRAINT pedido_compra_cab_pedido_compra_det_fk
FOREIGN KEY (pedcom_cod)
REFERENCES public.pedido_compra_cab (pedcom_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.pedido_presupuesto ADD CONSTRAINT pedido_compra_cab_pedido_presupuesto_fk
FOREIGN KEY (pedcom_cod)
REFERENCES public.pedido_compra_cab (pedcom_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.stock ADD CONSTRAINT depositos_stock_fk
FOREIGN KEY (dep_cod, suc_cod, emp_cod)
REFERENCES public.depositos (dep_cod, suc_cod, emp_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.compra_det ADD CONSTRAINT stock_compra_det_fk
FOREIGN KEY (itm_cod, tipitem_cod, dep_cod, suc_cod, emp_cod)
REFERENCES public.stock (itm_cod, tipitem_cod, dep_cod, suc_cod, emp_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.ajuste_invenario_det ADD CONSTRAINT stock_ajuste_invenario_det_fk
FOREIGN KEY (itm_cod, tipitem_cod, dep_cod, suc_cod, emp_cod)
REFERENCES public.stock (itm_cod, tipitem_cod, dep_cod, suc_cod, emp_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.ventas_det ADD CONSTRAINT stock_ventas_det_fk
FOREIGN KEY (itm_cod, tipitem_cod, dep_cod, suc_cod, emp_cod)
REFERENCES public.stock (itm_cod, tipitem_cod, dep_cod, suc_cod, emp_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.proveedor ADD CONSTRAINT tipo_proveedor_proveedor_fk
FOREIGN KEY (tiprov_cod)
REFERENCES public.tipo_proveedor (tiprov_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.presupuesto_prov_cab ADD CONSTRAINT proveedor_presupuesto_prov_cab_fk
FOREIGN KEY (pro_cod, tiprov_cod)
REFERENCES public.proveedor (pro_cod, tiprov_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.orden_compra_cab ADD CONSTRAINT proveedor_orden_compra_cab_fk
FOREIGN KEY (pro_cod, tiprov_cod)
REFERENCES public.proveedor (pro_cod, tiprov_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.compra_cab ADD CONSTRAINT proveedor_compra_cab_fk
FOREIGN KEY (pro_cod, tiprov_cod)
REFERENCES public.proveedor (pro_cod, tiprov_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.nota_compra_cab ADD CONSTRAINT proveedor_nota_compra_cab_fk
FOREIGN KEY (pro_cod, tiprov_cod)
REFERENCES public.proveedor (pro_cod, tiprov_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.compra_det ADD CONSTRAINT compra_cab_compra_det_fk
FOREIGN KEY (com_cod)
REFERENCES public.compra_cab (com_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.cuentas_pagar ADD CONSTRAINT compra_cab_cuentas_pagar_fk
FOREIGN KEY (com_cod)
REFERENCES public.compra_cab (com_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE public.libro_compras ADD CONSTRAINT compra_cab_libro_compras_fk
FOREIGN KEY (com_cod)
REFERENCES public.compra_cab (com_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.compra_orden ADD CONSTRAINT compra_cab_compra_orden_fk
FOREIGN KEY (com_cod)
REFERENCES public.compra_cab (com_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.nota_compra_cab ADD CONSTRAINT compra_cab_nota_compra_cab_fk
FOREIGN KEY (com_cod)
REFERENCES public.compra_cab (com_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.nota_compra_det ADD CONSTRAINT nota_compra_cab_nota_compra_det_fk
FOREIGN KEY (notacom_cod)
REFERENCES public.nota_compra_cab (notacom_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.orden_compra_det ADD CONSTRAINT orden_compra_cab_orden_compra_det_fk
FOREIGN KEY (ordcom_cod)
REFERENCES public.orden_compra_cab (ordcom_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.presupuesto_orden ADD CONSTRAINT orden_compra_cab_presupuesto_orden_fk
FOREIGN KEY (ordcom_cod)
REFERENCES public.orden_compra_cab (ordcom_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.compra_orden ADD CONSTRAINT orden_compra_cab_compra_orden_fk
FOREIGN KEY (ordcom_cod)
REFERENCES public.orden_compra_cab (ordcom_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.presupuesto_prov_det ADD CONSTRAINT presupuesto_prov_cab_presupuesto_prov_det_fk
FOREIGN KEY (presprov_cod)
REFERENCES public.presupuesto_prov_cab (presprov_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.pedido_presupuesto ADD CONSTRAINT presupuesto_prov_cab_pedido_presupuesto_fk
FOREIGN KEY (presprov_cod)
REFERENCES public.presupuesto_prov_cab (presprov_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

--AGREGADO
ALTER TABLE public.presupuesto_orden ADD CONSTRAINT presupuesto_prov_cab_presupuesto_orden_fk
FOREIGN KEY (presprov_cod)
REFERENCES public.presupuesto_prov_cab (presprov_cod)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

