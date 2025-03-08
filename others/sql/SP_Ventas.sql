-------------------------------REFERENCIALES-------------------------------

--sp_abm_tipoDocumento (TIPO_DOCUMENTO)
CREATE OR REPLACE FUNCTION sp_abm_tipoDocumento
(tipdoccod integer, 
tipdocdescri varchar, 
tipdocestado varchar,
operacion integer,
usucod integer,
usulogin varchar,
transaccion varchar)
RETURNS void
AS $$
declare tipdocaudit text;
begin --iniciar
	--se designan validaciones
	if operacion in (1,2) then
		perform * from tipo_documento
		where tipdoc_descri = upper(tipdocdescri) and tipdoc_cod != tipdoccod;
		if found then
			raise exception '1';
		elseif operacion = 1 then --realizamos un insert
			INSERT INTO public.tipo_documento
			(tipdoc_cod, 
			tipdoc_descri, 
			tipdoc_estado)
			VALUES(
			tipdoccod, 
			upper(tipdocdescri), 
			'ACTIVO');
			raise notice 'EL TIPO DE DOCUMENTO FUE REGISTRADO CON EXITO';
		elseif operacion = 2 then -- realizamos un update 
			UPDATE public.tipo_documento 
			SET tipdoc_descri=upper(tipdocdescri), 
			tipdoc_estado='ACTIVO'
			WHERE tipdoc_cod=tipdoccod;
			raise notice 'EL TIPO DE DOCUMENTO FUE MODIFICADO CON EXITO';
		end if;
	end if;
	if operacion = 3 then -- realizamos un update 
		update tipo_documento
		set tipdoc_estado = 'INACTIVO'
		WHERE tipdoc_cod = tipdoccod ;
		raise notice 'EL TIPO DE DOCUMENTO FUE BORRADO CON EXITO';
	end if;
	--se selecciona la ultima auditoria
	select coalesce(tipdoc_audit,'') into tipdocaudit
	from tipo_documento
	where tipdoc_cod = tipdoccod;

	--se actualiza la auditoria
	update tipo_documento
    set tipdoc_audit = tipdocaudit||' '||json_build_object(
        'usu_cod', usucod,
		'usu_login', usulogin,
        'fecha y hora', to_char(current_timestamp,'dd-mm-yyyy hh24:mi:ss'),
        'transaccion', upper(transaccion),
		'tipdoc_descri', upper(tipdocdescri), 
		'tipdoc_estado', upper(tipdocestado)
    )||','
    WHERE tipdoc_cod = tipdoccod;
end--finalizar
$$
language plpgsql;

--sp_abm_tipoComprobantes (TIPO COMPROBANTES)
CREATE OR REPLACE FUNCTION sp_abm_tipoComprobantes
(tipcompcod integer, 
tipcompdescri varchar, 
tipcompestado varchar,
operacion integer,
usucod integer,
usulogin varchar,
transaccion varchar)
RETURNS void
AS $$
declare tipcompaudit text;
begin --iniciar
	--se designan validaciones
	if operacion in (1,2) then
		perform * from tipo_comprobante
		where tipcomp_descri = upper(tipcompdescri) and tipcomp_cod != tipcompcod;
		if found then
			raise exception '1';
		elseif operacion = 1 then --realizamos un insert
			INSERT INTO tipo_comprobante
			(tipcomp_cod, 
			tipcomp_descri, 
			tipcomp_estado)
			VALUES(
			tipcompcod, 
			upper(tipcompdescri), 
			'ACTIVO');
			raise notice 'EL TIPO DE COMPROBANTE FUE REGISTRADO CON EXITO';
		elseif operacion = 2 then -- realizamos un update 
			UPDATE tipo_comprobante 
			SET tipcomp_descri = upper(tipcompdescri), 
			tipcomp_estado ='ACTIVO'
			WHERE tipcomp_cod = tipcompcod;
			raise notice 'EL TIPO DE COMPROBANTE FUE MODIFICADO CON EXITO';
		end if;
	end if;
	if operacion = 3 then -- realizamos un update 
		update tipo_comprobante
		set tipcomp_estado = 'INACTIVO'
		WHERE tipcomp_cod = tipcompcod ;
		raise notice 'EL TIPO DE COMPROBANTE FUE BORRADO CON EXITO';
	end if;
	--se selecciona la ultima auditoria
	select coalesce(tipcomp_audit,'') into tipcompaudit
	from tipo_comprobante
	where tipcomp_cod = tipcompcod;

	--se actualiza la auditoria
	update tipo_comprobante
    set tipcomp_audit = tipcompaudit||' '||json_build_object(
        'usu_cod', usucod,
		'usu_login', usulogin,
        'fecha y hora', to_char(current_timestamp,'dd-mm-yyyy hh24:mi:ss'),
        'transaccion', upper(transaccion),
		'tipcomp_descri', upper(tipcompdescri), 
		'tipcomp_estado', upper(tipcompestado)
    )||','
    WHERE tipcomp_cod = tipcompcod;
end--finalizar
$$
language plpgsql;

--sp_abm_formaCobro (FORMA COBRO)
CREATE OR REPLACE FUNCTION sp_abm_formaCobro
(forcobcod integer, 
forcobdescri varchar, 
forcobestado varchar,
operacion integer,
usucod integer,
usulogin varchar,
transaccion varchar)
RETURNS void
AS $$
declare forcobaudit text;
begin --iniciar
	--se designan validaciones
	if operacion in (1,2) then
		perform * from forma_cobro
		where forcob_descri = upper(forcobdescri) and forcob_cod != forcobcod;
		if found then
			raise exception '1';
		elseif operacion = 1 then --realizamos un insert
			INSERT INTO forma_cobro
			(forcob_cod, 
			forcob_descri, 
			forcob_estado)
			VALUES(
			forcobcod, 
			upper(forcobdescri), 
			'ACTIVO');
			raise notice 'LA FORMA DE COBRO FUE REGISTRADA CON EXITO';
		elseif operacion = 2 then -- realizamos un update 
			UPDATE forma_cobro 
			SET forcob_descri = upper(forcobdescri), 
			forcob_estado ='ACTIVO'
			WHERE forcob_cod = forcobcod;
			raise notice 'LA FORMA DE COBRO FUE MODIFICADA CON EXITO';
		end if;
	end if;
	if operacion = 3 then -- realizamos un update 
		update forma_cobro
		set forcob_estado = 'INACTIVO'
		WHERE forcob_cod = forcobcod ;
		raise notice 'LA FORMA DE COBRO FUE BORRADA CON EXITO';
	end if;
	--se selecciona la ultima auditoria
	select coalesce(forcob_audit,'') into forcobaudit
	from forma_cobro
	where forcob_cod = forcobcod;

	--se actualiza la auditoria
	update forma_cobro
    set forcob_audit = forcobaudit||' '||json_build_object(
        'usu_cod', usucod,
		'usu_login', usulogin,
        'fecha y hora', to_char(current_timestamp,'dd-mm-yyyy hh24:mi:ss'),
        'transaccion', upper(transaccion),
		'forcob_descri', upper(forcobdescri), 
		'forcob_estado', upper(forcobestado)
    )||','
    WHERE forcob_cod = forcobcod;
end--finalizar
$$
language plpgsql;

--sp_abm_marcaTarjeta (MARCA TARJETA)
CREATE OR REPLACE FUNCTION sp_abm_marcaTarjeta
(martarjcod integer, 
martarjdescri varchar, 
martarjestado varchar,
operacion integer,
usucod integer,
usulogin varchar,
transaccion varchar)
RETURNS void
AS $$
declare martarjaudit text;
begin --iniciar
	--se designan validaciones
	if operacion in (1,2) then
		perform * from marca_tarjeta
		where martarj_descri = upper(martarjdescri) and martarj_cod != martarjcod;
		if found then
			raise exception '1';
		elseif operacion = 1 then --realizamos un insert
			INSERT INTO marca_tarjeta
			(martarj_cod, 
			martarj_descri, 
			martarj_estado)
			VALUES(
			martarjcod,
			upper(martarjdescri), 
			'ACTIVO');
			raise notice 'LA MARCA DE LA TARJETA FUE REGISTRADA CON EXITO';
		elseif operacion = 2 then -- realizamos un update 
			UPDATE marca_tarjeta 
			SET martarj_descri = upper(martarjdescri), 
			martarj_estado ='ACTIVO'
			WHERE martarj_cod = martarjcod;
			raise notice 'LA MARCA DE LA TARJETA FUE MODIFICADA CON EXITO';
		end if;
	end if;
	if operacion = 3 then -- realizamos un update 
		update marca_tarjeta
		set martarj_estado = 'INACTIVO'
		WHERE martarj_cod = martarjcod ;
		raise notice 'LA MARCA DE LA TARJETA FUE BORRADA CON EXITO';
	end if;
	--se selecciona la ultima auditoria
	select coalesce(martarj_audit,'') into martarjaudit
	from marca_tarjeta
	where martarj_cod = martarjcod;

	--se actualiza la auditoria
	update marca_tarjeta
    set martarj_audit = martarjaudit||' '||json_build_object(
        'usu_cod', usucod,
		'usu_login', usulogin,
        'fecha y hora', to_char(current_timestamp,'dd-mm-yyyy hh24:mi:ss'),
        'transaccion', upper(transaccion),
		'martarj_descri', upper(martarjdescri), 
		'martarj_estado', upper(martarjestado)
    )||','
    WHERE martarj_cod = martarjcod;
end--finalizar
$$
language plpgsql;

--sp_abm_entidadEmisora (ENTIDAD EMISORA)
CREATE OR REPLACE FUNCTION sp_abm_entidadEmisora
(entcod integer, 
entrazonsocial varchar, 
entruc varchar,
enttelf varchar,
entemail varchar,
entestado varchar,
operacion integer,
usucod integer,
usulogin varchar,
transaccion varchar)
RETURNS void
AS $$
declare entaudit text;
begin --iniciar
	--se designan validaciones
	if operacion in (1,2) then
		perform * from entidad_emisora
		where (ent_razonsocial = upper(entrazonsocial) or ent_ruc = entruc) and ent_cod != entcod;
		if found then
			raise exception '1';
		elseif operacion = 1 then --realizamos un insert
			INSERT INTO entidad_emisora
			(ent_cod, 
			ent_razonsocial, 
			ent_ruc,
			ent_telf,
			ent_email,
			ent_estado)
			VALUES(
			entcod, 
			upper(entrazonsocial),
			entruc,
			enttelf,
			entemail,
			'ACTIVO');
			raise notice 'LA ENTIDAD FUE REGISTRADA CON EXITO';
		elseif operacion = 2 then -- realizamos un update 
			UPDATE entidad_emisora 
			SET ent_razonsocial = upper(entrazonsocial), 
			ent_ruc = entruc,
			ent_telf = enttelf,
			ent_email = entemail,
			ent_estado ='ACTIVO'
			WHERE ent_cod = entcod;
			raise notice 'LA ENTIDAD FUE MODIFICADA CON EXITO';
		end if;
	end if;
	if operacion = 3 then -- realizamos un update 
		update entidad_emisora
		set ent_estado = 'INACTIVO'
		WHERE ent_cod = entcod ;
		raise notice 'LA ENTIDAD FUE BORRADA CON EXITO';
	end if;
	--se selecciona la ultima auditoria
	select coalesce(ent_audit,'') into entaudit
	from entidad_emisora
	where ent_cod = entcod;

	--se actualiza la auditoria
	update entidad_emisora
    set ent_audit = entaudit||' '||json_build_object(
        'usu_cod', usucod,
		'usu_login', usulogin,
        'fecha y hora', to_char(current_timestamp,'dd-mm-yyyy hh24:mi:ss'),
        'transaccion', upper(transaccion),
		'ent_razonsocial', upper(entrazonsocial), 
		'ent_ruc', entruc, 
		'ent_telf', enttelf, 
		'ent_email', entemail, 
		'ent_estado', upper(entestado)
    )||','
    WHERE ent_cod = entcod;
end--finalizar
$$
language plpgsql;

--sp_abm_entidadAdherida (ENTIDAD ADHERIDA)
CREATE OR REPLACE FUNCTION sp_abm_entidadAdherida
(entahdcod integer, 
martarjcod integer, 
entcod integer,
entahdestado varchar,
operacion integer,
usucod integer,
usulogin varchar,
transaccion varchar,
entrazonsocial varchar,
martarjdescri varchar)
RETURNS void
AS $$
declare entahdaudit text;
begin --iniciar
	--se designan validaciones
	if operacion in (1,2) then
		perform * from entidad_adherida
		where (martarj_cod = martarjcod and ent_cod = entcod) and entahd_cod != entahdcod;
		if found then
			raise exception '1';
		elseif operacion = 1 then --realizamos un insert
			INSERT INTO entidad_adherida
			(entahd_cod,
			martarj_cod, 
			ent_cod, 
			entahd_estado)
			VALUES(
			entahdcod, 
			martarjcod,
			entcod,
			'ACTIVO');
			raise notice 'LA ENTIDAD ADHERIDA FUE REGISTRADA CON EXITO';
		elseif operacion = 2 then -- realizamos un update 
			UPDATE entidad_adherida 
			SET martarj_cod = martarjcod, 
			ent_cod = entcod,
			entahd_estado ='ACTIVO'
			WHERE entahd_cod = entahdcod;
			raise notice 'LA ENTIDAD ADHERIDA FUE MODIFICADA CON EXITO';
		end if;
	end if;
	if operacion = 3 then -- realizamos un update 
		update entidad_adherida
		set entahd_estado = 'INACTIVO'
		WHERE entahd_cod = entahdcod ;
		raise notice 'LA ENTIDAD ADHERIDA FUE BORRADA CON EXITO';
	end if;
	--se selecciona la ultima auditoria
	select coalesce(entahd_audit,'') into entahdaudit
	from entidad_adherida
	where entahd_cod = entahdcod;

	--se actualiza la auditoria
	update entidad_adherida
    set entahd_audit = entahdaudit||' '||json_build_object(
        'usu_cod', usucod,
		'usu_login', usulogin,
        'fecha y hora', to_char(current_timestamp,'dd-mm-yyyy hh24:mi:ss'),
        'transaccion', upper(transaccion),
		'ent_razonsocial', upper(entrazonsocial), 
		'martarj_descri', upper(martarjdescri), 
		'entahd_estado', upper(entahdestado)
    )||','
    WHERE entahd_cod = entahdcod;
end--finalizar
$$
language plpgsql;

--sp_abm_caja (CAJA)
CREATE OR REPLACE FUNCTION sp_abm_caja
(cajcod integer,
cajdescri varchar, 
cajestado varchar,
operacion integer,
usucod integer,
usulogin varchar,
transaccion varchar)
RETURNS void
AS $$
declare cajaudit text;
begin --iniciar
	--se designan validaciones
	if operacion in (1,2) then
		perform * from caja
		where caj_descri = upper(cajdescri) and caj_cod != cajcod;
		if found then
			raise exception '1';
		elseif operacion = 1 then --realizamos un insert
			INSERT INTO caja
			(caj_cod,
			caj_descri, 
			caj_estado)
			VALUES(
			cajcod,
			upper(cajdescri),
			'ACTIVO');
			raise notice 'LA CAJA FUE REGISTRADA CON EXITO';
		elseif operacion = 2 then -- realizamos un update 
			UPDATE caja 
			SET caj_descri = upper(cajdescri),
			caj_estado ='ACTIVO'
			WHERE caj_cod = cajcod;
			raise notice 'LA CAJA FUE MODIFICADA CON EXITO';
		end if;
	end if;
	if operacion = 3 then -- realizamos un update 
		update caja
		set caj_estado = 'INACTIVO'
		WHERE caj_cod = cajcod;
		raise notice 'LA CAJA FUE BORRADA CON EXITO';
	end if;
	--se selecciona la ultima auditoria
	select coalesce(caj_audit,'') into cajaudit
	from caja
	where caj_cod = cajcod;

	--se actualiza la auditoria
	update caja
    set caj_audit = cajaudit||' '||json_build_object(
        'usu_cod', usucod,
		'usu_login', usulogin,
        'fecha y hora', to_char(current_timestamp,'dd-mm-yyyy hh24:mi:ss'),
        'transaccion', upper(transaccion),
		'caj_descri', upper(cajdescri), 
		'caj_estado', upper(cajestado)
    )||','
    WHERE caj_cod = cajcod;
end--finalizar
$$
language plpgsql;

--sp_abm_clientes (CLIENTES)
CREATE OR REPLACE FUNCTION sp_abm_clientes
(clicod integer,  
clidireccion varchar,
cliestado varchar,
percod integer,
ciucod integer,
operacion integer,
usucod integer,
usulogin varchar,
transaccion varchar,
nombre varchar,
ciudescripcion varchar)
RETURNS void
AS $$
declare cliaudit text;
begin --iniciar
	--se designan validaciones
	if operacion in (1,2) then
		perform * from clientes
		where per_cod = percod and cli_cod != clicod;
		if found then
			raise exception '1';
		elseif operacion = 1 then --realizamos un insert
			INSERT INTO clientes
			(cli_cod,
			per_cod,
			ciu_cod,
			cli_direccion,
			cli_estado)
			VALUES(
			clicod,
			percod,
			ciucod,
			upper(clidireccion),
			'ACTIVO');
			raise notice 'EL CLIENTE FUE REGISTRADO CON EXITO';
		elseif operacion = 2 then -- realizamos un update 
			UPDATE clientes 
			set per_cod = percod,
			ciu_cod = ciucod,
			cli_direccion = upper(clidireccion),
			cli_estado ='ACTIVO'
			WHERE cli_cod = clicod;
			raise notice 'EL CLIENTE FUE MODIFICADO CON EXITO';
		end if;
	end if;
	if operacion = 3 then -- realizamos un update 
		update clientes
		set cli_estado = 'INACTIVO'
		WHERE cli_cod = clicod ;
		raise notice 'EL CLIENTE FUE BORRADO CON EXITO';
	end if;
	--se selecciona la ultima auditoria
	select coalesce(cli_audit,'') into cliaudit
	from clientes
	where cli_cod = clicod;

	--se actualiza la auditoria
	update clientes
    set cli_audit = cliaudit||' '||json_build_object(
        'usu_cod', usucod,
		'usu_login', usulogin,
        'fecha y hora', to_char(current_timestamp,'dd-mm-yyyy hh24:mi:ss'),
        'transaccion', upper(transaccion),
		'per_cod', percod, 
		'nombre', upper(nombre),
		'ciu_cod', ciucod,  
		'ciu_descripcion', upper(ciudescripcion), 
		'cli_direccion', upper(clidireccion), 
		'cli_estado', upper(cliestado)
    )||','
    WHERE cli_cod = clicod;
end--finalizar
$$
language plpgsql;

--sp_facturas (FACTURAS)
create or replace function sp_facturas (
	succod integer,
	empcod integer,
	cajcod integer,
	facnro varchar) 
returns void as
$$
begin
	perform * from facturas
	where suc_cod = succod
		and emp_cod = empcod
		and caj_cod = cajcod;
	if found then
		raise exception '1';
	else
		insert into facturas
			(suc_cod, 
			emp_cod, 
			caj_cod, 
			fac_nro)
		values
			(succod,
			empcod,
			cajcod,
			facnro);
		raise notice 'FACTURA REGISTADA CON EXITO';
	end if;
end;
$$
language plpgsql;

--sp_abm_red_pago (RED_PAGO)
CREATE OR REPLACE FUNCTION sp_abm_red_pago
(redpagcod integer, 
redpagdescri varchar, 
redpagestado varchar,
operacion integer,
usucod integer,
usulogin varchar,
transaccion varchar)
RETURNS void
AS $$
declare redpagaudit text;
begin --iniciar
	--se designan validaciones
	if operacion in (1,2) then
		perform * from red_pago
		where redpag_descri = upper(redpagdescri) and redpag_cod != redpagcod;
		if found then
			raise exception '1';
		elseif operacion = 1 then --realizamos un insert
			INSERT INTO public.red_pago
			(redpag_cod, 
			redpag_descri, 
			redpag_estado)
			VALUES(
			redpagcod, 
			upper(redpagdescri), 
			'ACTIVO');
			raise notice 'EL TIPO DE DOCUMENTO FUE REGISTRADO CON EXITO';
		elseif operacion = 2 then -- realizamos un update 
			UPDATE public.red_pago 
			SET redpag_descri=upper(redpagdescri), 
			redpag_estado='ACTIVO'
			WHERE redpag_cod=redpagcod;
			raise notice 'EL TIPO DE DOCUMENTO FUE MODIFICADO CON EXITO';
		end if;
	end if;
	if operacion = 3 then -- realizamos un update 
		update red_pago
		set redpag_estado = 'INACTIVO'
		WHERE redpag_cod = redpagcod ;
		raise notice 'EL TIPO DE DOCUMENTO FUE BORRADO CON EXITO';
	end if;
	--se selecciona la ultima auditoria
	select coalesce(redpag_audit,'') into redpagaudit
	from red_pago
	where redpag_cod = redpagcod;

	--se actualiza la auditoria
	update red_pago
    set redpag_audit = redpagaudit||' '||json_build_object(
        'usu_cod', usucod,
		'usu_login', usulogin,
        'fecha y hora', to_char(current_timestamp,'dd/mm/yyyy hh24:mi:ss'),
        'transaccion', upper(transaccion),
		'redpag_descri', upper(redpagdescri), 
		'redpag_estado', upper(redpagestado)
    )||','
    WHERE redpag_cod = redpagcod;
end--finalizar
$$
language plpgsql;


-------------------------------MOVIMIENTOS-------------------------------

--sp_pedido_venta_cab (PEDIDO VENTAS CABECERA)
CREATE OR REPLACE FUNCTION sp_pedido_venta_cab
(pedvencod integer,
pedvenestado varchar,
usucod integer,
succod integer,
empcod integer,
clicod integer,
operacion_cab integer,
usulogin varchar,
sucdescri varchar,
emprazonsocial varchar,
pernrodoc varchar,
cliente varchar,
transaccion varchar)
RETURNS void
AS $$
declare pedvenaudit text;
begin 
    if operacion_cab = 1 then
        -- aqui hacemos un insert
        INSERT INTO pedido_venta_cab 
	        (pedven_cod,
	        pedven_fecha, 
	        pedven_estado,
	        usu_cod, 
	        suc_cod,
	        emp_cod,
	        cli_cod)
        VALUES(
	        pedvencod,
	        current_date,
	      	'ACTIVO',
	        usucod,
	        succod,
	      	empcod,
	      	clicod);
        raise notice 'EL PEDIDO FUE REGISTADO CON EXITO';
    end if;
    if operacion_cab = 2 then
        -- aqui hacemos un update
		update pedido_venta_cab 
			SET pedven_estado = 'ANULADO',
			usu_cod = usucod
        WHERE pedven_cod = pedvencod;
        raise notice 'EL PEDIDO FUE ANULADO';
    end if;
	--se selecciona la ultima auditoria
	select coalesce(pedven_audit,'') into pedvenaudit
	from pedido_venta_cab
	where pedven_cod = pedvencod;

	--se actualiza la auditoria
	update pedido_venta_cab
    set pedven_audit = pedvenaudit||' '||json_build_object(
        'usu_cod', usucod,
		'usu_login', usulogin,
        'fecha y hora', to_char(current_timestamp,'dd-mm-yyyy hh24:mi:ss'),
        'transaccion', upper(transaccion),
        'cliente', upper(cliente),
        'nro_documento', pernrodoc,
        'emp_cod', empcod,
        'emp_razonsocial', upper(emprazonsocial),
        'suc_cod', succod,
		'suc_descri', upper(sucdescri), 
		'pedven_estado', upper(pedvenestado)
    )||','
    WHERE pedven_cod = pedvencod;
end
$$
language plpgsql;

--sp_pedido_venta_det (PEDIDO VENTAS DETALLE)
CREATE OR REPLACE FUNCTION sp_pedido_venta_det
(itmcod integer, 
tipitemcod integer, 
pedvencod integer, 
pedvendetcantidad numeric, 
pedvendetprecio numeric, 
operacion integer)
RETURNS void
AS $$
begin 
	if operacion = 1 then
		perform * from pedido_venta_det
		where itm_cod = itmcod and pedven_cod = pedvencod;
		if found then
			raise exception '1';
	    elseif operacion = 1 then
	        -- aqui hacemos un insert
	        INSERT INTO pedido_venta_det 
		        (itm_cod,
		        tipitem_cod, 
		        pedven_cod, 
		        pedvendet_cantidad,
		        pedvendet_precio)
	        VALUES(
		        itmcod,
		        tipitemcod,
		        pedvencod,
		        pedvendetcantidad,
		        pedvendetprecio);
	        raise notice 'EL DETALLE FUE REGISTADO CON EXITO';
	    end if;
	end if;
    if operacion = 2 then
    	-- aqui hacemos un delete
		delete from pedido_venta_det 
		where itm_cod = itmcod 
			and tipitem_cod = tipitemcod 
			and pedven_cod = pedvencod;
		raise notice 'EL DETALLE FUE ELIMINADO CON EXITO';
	end if;
end
$$
language plpgsql;

--sp_apertura_cierre (APERTURA Y CIERRE DE CAJA)
CREATE OR REPLACE FUNCTION sp_apertura_cierre
(cajcod integer,
succod integer,
empcod integer,
usucod integer,
apciercod integer,
apcierfechahoraaper timestamp,
apcierfechahoracierre timestamp,
apciermontoaper numeric,
apciermontocierre numeric,
apcierestado varchar,
operacion integer)
RETURNS void
AS $$
begin 
    if operacion = 1 then
    perform * from apertura_cierre
		where caj_cod = cajcod and apcier_cod <> apciercod and apcier_estado = 'ABIERTA';
		if found then
			raise exception '1';
	    elseif operacion = 1 then
        -- aqui hacemos un insert
			INSERT INTO apertura_cierre 
				(caj_cod,
				suc_cod, 
				emp_cod,
				usu_cod,
				apcier_cod,
				apcier_fechahora_aper,
				apcier_fechahora_cierre,
				apcier_monto_aper,
				apcier_monto_cierre,
				apcier_estado)
	        VALUES(
				cajcod,
				succod,
				empcod,
				usucod,
				apciercod,
				apcierfechahoraaper,
				apcierfechahoraaper,
				apciermontoaper,
				0,
				'ABIERTA');
	    	raise notice 'CAJA ABIERTA EXITOSAMENTE';
	  	end if;
    end if;
    if operacion = 2 then
        -- aqui hacemos un update
		update apertura_cierre 
			SET apcier_fechahora_cierre = apcierfechahoracierre,
			apcier_monto_cierre = apciermontocierre,
			apcier_estado = 'CERRADA',
			usu_cod = usucod
        WHERE apcier_cod = apciercod;
        raise notice 'CAJA CERRADA EXITOSAMENTE';
    end if;
end
$$
language plpgsql;

--sp_venta_cab (VENTA CABECERA)
CREATE OR REPLACE FUNCTION sp_venta_cab
(vencod integer,
venfecha date,
vennrofac varchar,
ventipfac tipofac,
vencuotas integer,
venmontocuota integer,
venintefecha varchar,
venestado varchar,
clicod integer,
usucod integer,
succod integer,
empcod integer,
ventimbrado varchar,
tipcompcod integer,
pedvencod integer,
prprcod integer,
operacion integer)
RETURNS void
AS $$
declare 
	vendet record;
	libvencod integer:= (select coalesce (max(libven_cod),0)+1 from libro_ventas);
	venpedcod integer:= (select coalesce (max(venped_cod),0)+1 from venta_pedido);
	prevencod integer:= (select coalesce (max(preven_cod),0)+1 from presupuesto_venta);
begin 
    if operacion in (1,2) then
		perform * from ventas_cab
		where ven_nrofac = vennrofac 
			and ven_estado = 'ACTIVO';
		if found then
			raise exception '1';
	    elseif operacion in (1,2) then
        -- aqui hacemos un insert
	        INSERT INTO ventas_cab 
		        (ven_cod,
		        ven_fecha,
		        ven_nrofac,
		        ven_tipfac,
		        ven_cuotas,
		        ven_montocuota,
		        ven_intefecha,
		        ven_estado,
		        cli_cod,
		        usu_cod,
		        suc_cod,
		        emp_cod,
				ven_timbrado,
				tipcomp_cod)
	        VALUES
		        (vencod,
		        venfecha,
		        vennrofac,
		        ventipfac,
		        vencuotas,
		        venmontocuota,
		        upper(venintefecha),
		      	'ACTIVO',
		      	clicod,
		    	usucod,
		        succod,
		      	empcod,
				ventimbrado,
				tipcompcod);
	      --SE INSERTAN DATOS EN libro_ventas
	        insert into libro_ventas
		        (ven_cod,
		        libven_cod,
		        libven_fecha,
		        libven_nrocomprobante,
		        libven_excenta,
		        libven_iva5,
		        libven_iva10,
		        libven_estado,
				tipcomp_cod)
	        values
		        (vencod,
		        libvencod,
		        venfecha,
		        vennrofac,
		        0,
		        0,
		        0,
		        'ACTIVO',
				tipcompcod);
	      --INSERTA DATOS EN cuentas_cobrar
	        insert into cuentas_cobrar 
		        (ven_cod,
		        cuencob_cuotas,
		        cuencob_monto,
		        cuencob_saldo,
		        cuencob_estado,
				tipcomp_cod)
	        values
		        (vencod,
		        vencuotas,
		        0,
		        0,
		        'ACTIVO',
				tipcompcod);
	    	if operacion = 1 then
		      --INSERTA DATOS EN venta_pedido
			    INSERT INTO venta_pedido
				    (venped_cod,
				    ven_cod,
				    pedven_cod)
			    values
				    (venpedcod,
			        vencod,
				    pedvencod);
			  --SE MODIFICA EL ESTADO DE pedido_venta_cab
			   	UPDATE pedido_venta_cab 
					SET pedven_estado = 'VENDIDO',
					usu_cod = usucod
		        WHERE pedven_cod = pedvencod;
		    elseif operacion = 2 then
		    --INSERTA DATOS EN presupuesto_venta
			    INSERT INTO presupuesto_venta
				    (preven_cod,
				    ven_cod,
				    prpr_cod)
			    values
				    (prevencod,
			        vencod,
				    prprcod);
			 --SE MODIFICA EL ESTADO DE presupuesto_prep_cab
			   	UPDATE presupuesto_prep_cab  
					SET prpr_estado = 'APROBADO',
					usu_cod = usucod
		        WHERE prpr_cod = prprcod;
		    end if;
		   	raise notice 'LA VENTA FUE REGISTADA CON EXITO';
	    end if;
    end if;
    if operacion in (3,4) then
        -- aqui hacemos un update
		update ventas_cab 
			SET ven_estado = 'ANULADO',
			usu_cod = usucod
        WHERE ven_cod = vencod;
       --ANULAMOS LIBRO VENTAS
        update libro_ventas 
        	SET libven_estado = 'ANULADO'
        WHERE ven_cod = vencod;
        --ANULAMOS CUENTAS COBRAR
        update cuentas_cobrar 
        	SET cuencob_estado = 'ANULADO'
        WHERE ven_cod = vencod;
		if operacion = 3 then
	        -- ACTUALIZA STOCK (SUMA)
			for vendet in select * from ventas_det where ven_cod = vencod loop
				UPDATE stock
					set sto_cantidad = sto_cantidad + vendet.vendet_cantidad
				where itm_cod = vendet.itm_cod
					and tipitem_cod = vendet.tipitem_cod
					and dep_cod = vendet.dep_cod
					and suc_cod = vendet.suc_cod
					and emp_cod = vendet.emp_cod;
			end loop;
	       	--SE MODIFICA EL ESTADO DE pedido_venta_cab
			UPDATE pedido_venta_cab 
				SET pedven_estado = 'ACTIVO',
					usu_cod = usucod
	        WHERE pedven_cod = pedvencod;
	    elseif operacion = 4 then
       		--SE MODIFICA EL ESTADO DE presupuesto_prep_cab
			UPDATE presupuesto_prep_cab 
				SET prpr_estado = 'ACTIVO',
					usu_cod = usucod
	        WHERE prpr_cod = prprcod;
	    end if;
		raise notice 'LA VENTA FUE ANULADA CON EXITO';
    end if;
end
$$
language plpgsql;

--sp_venta_det (VENTA DETALLE)
CREATE OR REPLACE FUNCTION sp_venta_det
(vencod integer, 
itmcod integer, 
tipitemcod integer, 
depcod integer,
succod integer,
empcod integer,
vendetcantidad numeric, 
vendetprecio integer,
operacion integer)
RETURNS void
AS $$
begin 
	if operacion = 1 then
		perform * from ventas_det
		where itm_cod = itmcod and vencod = vencod;
		if found then
			raise exception '1';
	    elseif operacion = 1 then
	        -- aqui hacemos un insert
	        INSERT INTO ventas_det 
		        (ven_cod,
		        itm_cod,
		        tipitem_cod, 
		        dep_cod, 
		        suc_cod,
		        emp_cod,
		        vendet_cantidad,
		        vendet_precio)
	        VALUES(
		        vencod,
		        itmcod,
		        tipitemcod,
		        depcod,
		        succod,
		        empcod,
		        vendetcantidad,
		        vendetprecio);
	       -- ACTUALIZA STOCK (RESTA)
			UPDATE stock 
				set sto_cantidad = sto_cantidad - vendetcantidad
			where itm_cod = itmcod
				and tipitem_cod = tipitemcod
				and dep_cod = depcod
				and suc_cod = succod
				and emp_cod = empcod;
			raise notice 'EL DETALLE FUE REGISTADO CON EXITO';
	    end if;
	end if;
    if operacion = 2 then
    	-- aqui hacemos un delete
		delete from ventas_det
		where itm_cod = itmcod
				and tipitem_cod = tipitemcod
				and dep_cod = depcod
				and suc_cod = succod
				and emp_cod = empcod
				and ven_cod = vencod;
		-- ACTUALIZA STOCK (SUMA)
			UPDATE stock
				set sto_cantidad = sto_cantidad + vendetcantidad
			where itm_cod = itmcod
				and tipitem_cod = tipitemcod
				and dep_cod = depcod
				and suc_cod = succod
				and emp_cod = empcod;
		raise notice 'EL DETALLE FUE ELIMINADO CON EXITO';
	end if;
end
$$
language plpgsql;

--sp_cuentas_cobrar (CUENTAS A COBRAR)
create or replace function sp_cuentas_cobrar
(vencod integer,
monto integer,
saldo integer,
operacion integer)
returns void
as $$
begin
	if operacion = 1 then
		update cuentas_cobrar 
			set cuencob_monto = cuencob_monto + monto,
			cuencob_saldo = cuencob_saldo + saldo
		where ven_cod = vencod;
	end if;
	if operacion = 2 then
		update cuentas_cobrar 
			set cuencob_monto = cuencob_monto - monto,
			cuencob_saldo = cuencob_saldo - saldo
		where ven_cod = vencod;
	end if;
end
$$
language plpgsql;

--sp_libro_ventas (LIBRO VENTAS)
create or replace function sp_libro_ventas
(vencod integer,
libvennrocomprobante varchar,
excenta numeric,
iva5 numeric,
iva10 numeric,
tipcompcod integer,
operacion integer)
returns void
as $$
begin
	if operacion = 1 then
		update libro_ventas 
		set libven_excenta = libven_excenta + excenta,
		libven_iva5 = libven_iva5 + iva5,
		libven_iva10 = libven_iva10 + iva10
		where ven_cod = vencod
			and libven_nrocomprobante = libvennrocomprobante
			and tipcomp_cod = tipcompcod;
	end if;
	if operacion = 2 then
		update libro_ventas 
		set libven_excenta = libven_excenta - excenta,
		libven_iva5 = libven_iva5 - iva5,
		libven_iva10 = libven_iva10 - iva10
		where ven_cod = vencod
			and libven_nrocomprobante = libvennrocomprobante
			and tipcomp_cod = tipcompcod;
	end if;
end
$$
language plpgsql;

--sp_cobros_cab (CUENTAS CABECERA)
CREATE OR REPLACE FUNCTION sp_cobros_cab
(cobrcod integer,
cobrfecha date,
cobrestado varchar,
cajcod integer,
succod integer,
empcod integer,
usucod integer,
apciercod integer,
operacion integer)
RETURNS void
AS $$
declare cobdet record;
declare cobtarj record;
declare cobcheq record;
declare ultcod integer;
begin 
	ultcod = (select coalesce (max(cobr_cod),0)+1 from cobros_cab);
	if operacion = 1 then
	    -- aqui hacemos un insert
		INSERT INTO cobros_cab 
	    (cobr_cod,
		cobr_fecha,
		cobr_estado,
		caj_cod,
		suc_cod,
		emp_cod,
		usu_cod,
		apcier_cod)
		VALUES(
		ultcod,
		cobrfecha,
		'ACTIVO',
		cajcod,
		succod,
		empcod,
		usucod,
		apciercod);
		raise notice 'EL COBRO FUE REGISTADO CON EXITO';
    end if;
    if operacion = 2 then
        -- aqui hacemos un update de COBRO CABACERA
		update cobros_cab 
		SET cobr_estado = 'ANULADO'
        WHERE cobr_cod = cobrcod;
        -- ACTUALIZA CUENTAS COBRAR (SUMA EFECTIVO)
		for cobdet in select * from cobros_det where cobr_cod = cobrcod loop
			UPDATE cuentas_cobrar
			set cuencob_saldo = cuencob_saldo + cobdet.cobrdet_monto,
			cuencob_estado = 'ACTIVO'
			where ven_cod = cobdet.ven_cod;
		end loop;
		raise notice 'EL COBRO FUE ANULADO CON EXITO';
    end if;
end
$$
language plpgsql;

--sp_cobros_det (COBRO DETALLE)
CREATE OR REPLACE FUNCTION sp_cobros_det
(vencod integer, 
cobrcod integer, 
cobrdetcod integer, 
cobrdetmonto integer,
cobrdetnrocuota integer,
forcobcod integer, 
cobrtarjnum varchar,
entahdcod integer, 
cobrcheqnum varchar,
entcod integer,
operacion integer)
RETURNS void
AS $$
begin
		if operacion = 1 and forcobcod = 3 then
			perform * from cobro_tarjeta
			where cobrtarj_num = cobrtarjnum and entahd_cod = entahdcod and ven_cod = vencod and cobr_cod = cobrcod;
		    if found then
				raise exception 'tarjeta';
		    end if;
		end if;
		if operacion = 1 and forcobcod = 1 then
			perform * from cobro_cheque
			where cobrcheq_num = cobrcheqnum and ent_cod = entcod;
		    if found then
				raise exception 'cheque';
			end if;
		end if;
		if operacion = 1 then
			-- aqui hacemos un insert
		    INSERT INTO cobros_det 
		    (ven_cod,
		    cobr_cod,
		    cobrdet_cod, 
		    cobrdet_monto, 
		    cobrdet_nrocuota,
		    forcob_cod)
		    VALUES(
		    vencod,
		    cobrcod,
		    cobrdetcod,
		    cobrdetmonto,
		    cobrdetnrocuota,
		    forcobcod);
		    -- ACTUALIZA CUENTAS A COBRAR (RESTA)
			UPDATE cuentas_cobrar  
			set cuencob_saldo = cuencob_saldo - cobrdetmonto
			where ven_cod = vencod;
			raise notice 'EL DETALLE FUE REGISTADO CON EXITO';
	end if;
    if operacion = 2 then
    	if forcobcod = 1 then
    		-- aqui hacemos un delete de cobro cheque
			delete from cobro_cheque 
			where 
			cobrdet_cod = cobrdetcod;
		elseif forcobcod = 3 then
	    	-- aqui hacemos un delete
			delete from cobro_tarjeta 
			where 
			cobrdet_cod = cobrdetcod;
			raise notice 'EL COBRO DE TARJETA FUE ELIMINADO CON EXITO';
		end if;
		raise notice 'EL COBRO DE CHEQUE FUE ELIMINADO CON EXITO';
    	-- aqui hacemos un delete
		delete from cobros_det 
		where 
		cobrdet_cod = cobrdetcod;
		-- ACTUALIZA CUENTAS A COBRAR (SUMA)
		update cuentas_cobrar  
		set cuencob_saldo = cuencob_saldo + cobrdetmonto,
		cuencob_estado = 'ACTIVO'
		where ven_cod = vencod;
		raise notice 'EL DETALLE FUE ELIMINADO CON EXITO';
	end if;
end
$$
language plpgsql;

--sp_cobro_cheque (COBRO CHEQUE)
CREATE OR REPLACE FUNCTION sp_cobro_cheque
(cobrcheqcod integer, 
cobrcheqnum varchar, 
cobrcheqmonto numeric, 
cobrcheqtipcheq tipocheq,
cobrcheqfechaven date,
vencod integer,
cobrcod integer,
cobrdetcod integer,
entcod integer,
operacion integer)
RETURNS void
AS $$
declare ultcod integer;
begin 
	ultcod = (select coalesce (max(cobrcheq_cod),0)+1 from cobro_cheque);
	if operacion = 1 then
	        -- aqui hacemos un insert
	        INSERT INTO cobro_cheque 
	        (cobrcheq_cod,
	        cobrcheq_num,
	        cobrcheq_monto,
	        cobrcheq_tipcheq,
	        cobrcheq_fechaven,
	        ven_cod,
	        cobr_cod,
	        cobrdet_cod,
	        ent_cod)
	        VALUES(
	        ultcod,
	        cobrcheqnum,
	        cobrcheqmonto,
	        cobrcheqtipcheq,
	        cobrcheqfechaven,
	       	vencod,
	       	cobrcod,
	       	cobrdetcod,
	       	entcod);
	        raise notice 'EL COBRO DE CHEQUE FUE REGISTADO CON EXITO';
	end if;
end
$$
LANGUAGE plpgsql;

--sp_cobro_tarjeta (COBRO TARJETA)
CREATE OR REPLACE FUNCTION sp_cobro_tarjeta
(cobrtarjcod integer, 
cobrtarjnum varchar, 
cobrtarjmonto numeric, 
cobrtarjtiptarj tipotarj,
vencod integer,
cobrcod integer,
cobrdetcod integer,
martarjcod integer,
entcod integer,
entahdcod integer,
operacion integer)
RETURNS void
AS $$
declare ultcod integer;
begin 
	ultcod = (select coalesce (max(cobrtarj_cod),0)+1 from cobro_tarjeta);
	if operacion = 1 then
	        -- aqui hacemos un insert
	        INSERT INTO cobro_tarjeta 
	        (cobrtarj_cod,
	        cobrtarj_num,
	        cobrtarj_monto,
	        cobrtarj_tiptarj,
	        ven_cod,
	        cobr_cod,
	        cobrdet_cod,
	        martarj_cod,
	        ent_cod,
	        entahd_cod)
	        VALUES(
	        ultcod,
	        cobrtarjnum,
	        cobrtarjmonto,
	        cobrtarjtiptarj,
	       	vencod,
	       	cobrcod,
	       	cobrdetcod,
	       	martarjcod,
	       	entcod,
	       	entahdcod);
	        raise notice 'EL COBRO DE TARJETA FUE REGISTADO CON EXITO';
	end if;
end
$$
LANGUAGE plpgsql;

--sp_nota_venta_cab (NOTA VENTA CABECERA)
CREATE OR REPLACE FUNCTION sp_nota_venta_cab
(notvencod integer,
notvenfecha date,
notvennronota varchar,
notvenconcepto varchar,
notvenestado varchar,
tipcompcod integer,
vencod integer,
succod integer,
empcod integer,
usucod integer,
clicod integer,
operacion integer)
RETURNS void
AS $$
declare ultcod integer;
begin 
	ultcod = (select coalesce (max(notven_cod),0)+1 from nota_venta_cab);
    if operacion = 1 then
		perform * from nota_venta_cab
		where notven_nronota = notvennronota and notven_estado = 'ACTIVO';
		if found then
			raise exception 'repe';
	    elseif operacion = 1 then
        -- aqui hacemos un insert
	        INSERT INTO nota_venta_cab 
	        (notven_cod,
	        notven_fecha,
	        notven_nronota, 
	        notven_concepto,
	        notven_estado,
	        tipcomp_cod,
	        ven_cod,
	        suc_cod,
	        emp_cod,
	        usu_cod,
	        cli_cod)
	        VALUES(
	        ultcod,
	        notvenfecha,
	        notvennronota,
	        upper(notvenconcepto),
	      	'ACTIVO',
	      	tipcompcod,
	      	vencod,
	        succod,
	      	empcod,
	    	usucod,
	    	clicod);
	    	raise notice 'LA NOTA FUE REGISTADA CON EXITO';
	  	end if;
    end if;
    if operacion = 2 then
        -- aqui hacemos un update
		update nota_venta_cab 
		SET notven_estado = 'ANULADO'
        WHERE notven_cod = notvencod;
        raise notice 'EL NOTA FUE ANULADA';
    end if;
end
$$
language plpgsql;

--sp_nota_venta_det (NOTA VENTA DETALLE)
CREATE OR REPLACE FUNCTION sp_nota_venta_det
(itmcod integer, 
tipitemcod integer, 
notvencod integer, 
notvendetcantidad integer, 
notvendetprecio numeric, 
operacion integer)
RETURNS void
AS $$
begin 
	if operacion = 1 then
		perform * from nota_venta_det
		where itm_cod = itmcod and notven_cod = notvencod;
		if found then
			raise exception 'repedet';
	    elseif operacion = 1 then
	        -- aqui hacemos un insert
	        INSERT INTO nota_venta_det 
	        (itm_cod,
	        tipitem_cod, 
	        notven_cod, 
	        notvendet_cantidad,
	        notvendet_precio)
	        VALUES(
	        itmcod,
	        tipitemcod,
	        notvencod,
	        notvendetcantidad,
	        notvendetprecio);
	        raise notice 'EL DETALLE FUE REGISTADO CON EXITO';
	    end if;
	end if;
    if operacion = 2 then
    	-- aqui hacemos un delete
		delete from nota_venta_det 
		where 
		itm_cod = itmcod 
		and tipitem_cod = tipitemcod 
		and notven_cod = notvencod;
		raise notice 'EL DETALLE FUE ELIMINADO EXITOSAMENTE';
	end if;
end
$$
language plpgsql;
-------------------------------VISTAS-------------------------------

--v_facturas (FACTURAS)
create or replace view v_facturas as 
select
	s.suc_descri,
	c.caj_descri,
	lpad(cast(f.suc_cod as text), 3, '0')|| '-' || 
	lpad(cast(f.caj_cod as text), 3, '0')|| '-' || f.fac_nro as factura
from facturas f 
	join sucursales s on s.suc_cod = f.suc_cod and s.emp_cod = f.emp_cod 
		join empresa e on e.emp_cod = s.emp_cod 
	join caja c on c.caj_cod = f.caj_cod
order by f.suc_cod, f.caj_cod;

--v_pedido_venta_cab (PEDIDO VENTAS CABECERA)
create or replace view v_pedido_venta_cab as
select 
pvc.pedven_cod,
to_char(pvc.pedven_fecha, 'dd/mm/yyyy') as pedven_fecha,
pvc.emp_cod,
e.emp_razonsocial,
pvc.suc_cod,
s.suc_descri,
pvc.usu_cod,
u.usu_login,
pvc.cli_cod,
p.per_nrodoc,
p.per_nombres||' '||p.per_apellidos as cliente,
pvc.pedven_estado
from pedido_venta_cab pvc 
	join usuarios u on u.usu_cod = pvc.usu_cod
	join sucursales s on s.suc_cod = pvc.suc_cod and s.emp_cod = pvc.emp_cod
		join empresa e on e.emp_cod = s.emp_cod
	join clientes c on c.cli_cod = pvc.cli_cod
		join personas p on p.per_cod = c.per_cod 
where pvc.pedven_estado = 'ACTIVO' 
order by pvc.pedven_cod;

--v_pedido_venta_det (PEDIDO VENTAS DETALLE)
create or replace view v_pedido_venta_det as
select 
pvd.pedven_cod,
pvd.itm_cod,
pvd.tipitem_cod,
i.tipimp_cod,
i.itm_descri,
pvd.pedvendet_cantidad,
um.uni_descri||' ('||um.uni_simbolo||')' as uni_descri,
pvd.pedvendet_precio,
(case i.tipimp_cod when 1 then pvd.pedvendet_cantidad * pvd.pedvendet_precio else 0 end) as exenta,
(case i.tipimp_cod when 2 then pvd.pedvendet_cantidad * pvd.pedvendet_precio else 0 end) as iva5,
(case i.tipimp_cod when 3 then pvd.pedvendet_cantidad * pvd.pedvendet_precio else 0 end) as iva10
from pedido_venta_det pvd 
	join items i on i.itm_cod = pvd.itm_cod and i.tipitem_cod = pvd.tipitem_cod
		join tipo_item ti on ti.tipitem_cod = i.tipitem_cod 
		join unidad_medida um on um.uni_cod = i.uni_cod 
order by pvd.pedven_cod;

--v_venta_cab (VENTAS CABECERA 1)
create or replace view v_venta_cab as
select 
	vc.*,
	pvc.pedven_cod,
	p.per_nrodoc,
	p.per_nombres||' '||p.per_apellidos as cliente,
	u.usu_login,
	s.suc_descri,
	e.emp_razonsocial,
	to_char(vc.ven_fecha, 'dd/mm/yyyy') as ven_fecha2
from ventas_cab vc 
	join usuarios u on u.usu_cod = vc.usu_cod
	join sucursales s on s.suc_cod = vc.suc_cod and s.emp_cod = vc.emp_cod
	     join empresa e on e.emp_cod = s.emp_cod
	join clientes c on c.cli_cod = vc.cli_cod
		join personas p on p.per_cod = c.per_cod
	join venta_pedido vp on vp.ven_cod = vc.ven_cod
		join pedido_venta_cab pvc on pvc.pedven_cod = vp.pedven_cod
where vc.ven_estado = 'ACTIVO'
order by vc.ven_cod;

--v_venta_cab2 (VENTAS CABECERA 2)
create or replace view v_venta_cab2 as
select 
	vc.*,
	ppc.prpr_cod,
	p.per_nrodoc,
	p.per_nombres||' '||p.per_apellidos as cliente,
	u.usu_login,
	s.suc_descri,
	e.emp_razonsocial,
	to_char(vc.ven_fecha, 'dd/mm/yyyy') as ven_fecha2
from ventas_cab vc 
	join usuarios u on u.usu_cod = vc.usu_cod
	join sucursales s on s.suc_cod = vc.suc_cod and s.emp_cod = vc.emp_cod
	     join empresa e on e.emp_cod = s.emp_cod
	join clientes c on c.cli_cod = vc.cli_cod
		join personas p on p.per_cod = c.per_cod
	join presupuesto_venta pv on pv.ven_cod = vc.ven_cod
		join presupuesto_prep_cab ppc on ppc.prpr_cod = pv.prpr_cod
where vc.ven_estado = 'ACTIVO'
order by vc.ven_cod;

--v_venta_det (VENTAS DETALLE)
create or replace view v_venta_det as
select 
	vd.*,
	um.uni_descri||' ('||um.uni_simbolo||')' as uni_descri,
	vd.vendet_cantidad * vd.vendet_precio as total,
	(case i.tipimp_cod when 1 then vd.vendet_cantidad * vd.vendet_precio else 0 end) as exenta,
	(case i.tipimp_cod when 2 then vd.vendet_cantidad * vd.vendet_precio else 0 end) as iva5,
	(case i.tipimp_cod when 3 then vd.vendet_cantidad * vd.vendet_precio else 0 end) as iva10,
	i.itm_descri,
	i.tipimp_cod,
	d.dep_descri
from ventas_det vd
	join stock s on s.itm_cod = vd.itm_cod and s.tipitem_cod = vd.tipitem_cod and s.dep_cod = vd.dep_cod and s.suc_cod = vd.suc_cod and s.emp_cod = vd.emp_cod 
		join items i on i.itm_cod = s.itm_cod and i.tipitem_cod = s.tipitem_cod
			join tipo_item ti on ti.tipitem_cod = i.tipitem_cod
			join unidad_medida um on um.uni_cod = i.uni_cod 
		join depositos d on d.dep_cod = s.dep_cod and d.suc_cod = s.suc_cod and d.emp_cod = s.emp_cod
order by vd.ven_cod;



-----------------------------------------------------------TRIGGERS----------------------------------------------------------

--tg_facturas_auditoria (AUDITORIA DE FACTURAS)-------------------------------------------------------------------------------
create or replace function sp_facturas_auditoria()
returns trigger as 
$$
begin
	-- Si la operacion es actualizar el registro
    if (TG_OP = 'UPDATE') then
		--ANTERIOR
        insert into facturas_auditoria (
			facaudi_operacion,
			suc_cod, 
			emp_cod, 
			caj_cod,
			fac_nro)
        values (
			TG_OP||' - REG. ANTERIOR',
			old.suc_cod, 
			old.emp_cod, 
			old.caj_cod, 
			old.fac_nro);
		--NUEVO
        insert into facturas_auditoria (
			facaudi_operacion,
			suc_cod, 
			emp_cod, 
			caj_cod, 
			fac_nro)
        values (
			TG_OP||' - REG. ACTUAL',
			new.suc_cod, 
			new.emp_cod, 
			new.caj_cod, 
			new.fac_nro);
	-- Si la operacion es insertar un registro
    elseif (TG_OP = 'INSERT') then
        insert into facturas_auditoria (
			facaudi_operacion,
			suc_cod, 
			emp_cod, 
			caj_cod, 
			fac_nro)
        values (
			TG_OP,
			new.suc_cod, 
			new.emp_cod, 
			new.caj_cod, 
			new.fac_nro);
	end if;
	-- Se retorna null para cerrar la funcion
    return null; 
end;
$$
language plpgsql;

create trigger tg_facturas_auditoria
after insert or update on facturas
for each row execute function sp_facturas_auditoria();


--tg_pedido_venta_det_auditoria (Auditoria de pedido venta detalle)-------------------------------------------------------------------------------
create or replace function sp_pedido_venta_det_auditoria() 
returns trigger
as $$
	-- Se declaran los codigos y nombres de usuario para la tabla de auditoria
	declare
		usu_cod_old integer := (select pvc.usu_cod from pedido_venta_cab pvc where pvc.pedven_cod = old.pedven_cod);
		usu_login_old varchar := (select u.usu_login from usuarios u where u.usu_cod = usu_cod_old);
		usu_cod_new integer := (select pvc.usu_cod from pedido_venta_cab pvc where pvc.pedven_cod = new.pedven_cod);
		usu_login_new varchar := (select u.usu_login from usuarios u where u.usu_cod = usu_cod_new);
	begin
		-- Si la operacion es eliminar el registro
	    if (TG_OP = 'DELETE') then
	        insert into pedido_venta_det_auditoria (
				pvdaudi_operacion,
				usu_cod,
				usu_login,
				itm_cod,
				tipitem_cod,
				pedven_cod, 
				pedvendet_cantidad, 
				pedvendet_precio)
	        values (
				TG_OP, 
				usu_cod_old, 
				usu_login_old,
				old.itm_cod,
				old.tipitem_cod,
				old.pedven_cod,
				old.pedvendet_cantidad,
				old.pedvendet_precio);
		-- Si la operacion es insertar un registro
	    elseif (TG_OP = 'INSERT') then
	        insert into pedido_venta_det_auditoria (
				pvdaudi_operacion,
				usu_cod,
				usu_login,
				itm_cod,
				tipitem_cod,
				pedven_cod, 
				pedvendet_cantidad, 
				pedvendet_precio)
	        values (
				TG_OP, 
				usu_cod_new, 
				usu_login_new,
				new.itm_cod,
				new.tipitem_cod,
				new.pedven_cod,
				new.pedvendet_cantidad,
				new.pedvendet_precio);
	    end if;
		-- Se retorna null para cerrar la funcion
	    return null; 
	end; 
$$
language plpgsql;

create trigger tg_pedido_venta_det_auditoria
after insert or delete on pedido_venta_det
for each row execute function sp_pedido_venta_det_auditoria();

--tg_apertura_cierre_auditoria (Aduitoria de APERTURA Y CIERRE DE CAJA)-------------------------------------------------------------------------------
create or replace function sp_apertura_cierre_auditoria() 
returns trigger
as $$
	begin
		-- Si la operacion es actualizar el registro
	    if (TG_OP = 'UPDATE') then
			--ANTERIOR
	        insert into apertura_cierre_auditoria (
				apciaudi_operacion,
				caj_cod, 
				suc_cod, 
				emp_cod, 
				usu_cod,  
				apcier_cod, 
				apcier_fechahora_aper, 
				apcier_fechahora_cierre, 
				apcier_monto_aper, 	
				apcier_monto_cierre, 
				apcier_estado)
	        values (
				TG_OP||' - REG. ANTERIOR',
				old.caj_cod, 
				old.suc_cod, 
				old.emp_cod, 
				old.usu_cod, 
				old.apcier_cod, 
				old.apcier_fechahora_aper, 
				old.apcier_fechahora_cierre, 
				old.apcier_monto_aper, 	
				old.apcier_monto_cierre, 
				old.apcier_estado);
			--NUEVO
	        insert into apertura_cierre_auditoria (
				apciaudi_operacion,
				caj_cod, 
				suc_cod, 
				emp_cod, 
				usu_cod, 
				apcier_cod, 
				apcier_fechahora_aper, 
				apcier_fechahora_cierre, 
				apcier_monto_aper, 	
				apcier_monto_cierre, 
				apcier_estado)
	        values (
				TG_OP||' - REG. ACTUAL',
				new.caj_cod, 
				new.suc_cod, 
				new.emp_cod, 
				new.usu_cod, 
				new.apcier_cod, 
				new.apcier_fechahora_aper, 
				new.apcier_fechahora_cierre, 
				new.apcier_monto_aper, 	
				new.apcier_monto_cierre, 
				new.apcier_estado);
		-- Si la operacion es insertar un registro
	    elseif (TG_OP = 'INSERT') then
	        insert into apertura_cierre_auditoria (
				apciaudi_operacion,
				caj_cod, 
				suc_cod, 
				emp_cod, 
				usu_cod, 
				apcier_cod, 
				apcier_fechahora_aper, 
				apcier_fechahora_cierre, 
				apcier_monto_aper, 	
				apcier_monto_cierre, 
				apcier_estado)
	        values (
				TG_OP,
				new.caj_cod, 
				new.suc_cod, 
				new.emp_cod, 
				new.usu_cod, 
				new.apcier_cod, 
				new.apcier_fechahora_aper, 
				new.apcier_fechahora_cierre, 
				new.apcier_monto_aper, 	
				new.apcier_monto_cierre, 
				new.apcier_estado);
	    end if;
		-- Se retorna null para cerrar la funcion
	    return null; 
	end; 
$$
language plpgsql;

create trigger tg_apertura_cierre_auditoria
after insert or update on apertura_cierre
for each row execute function sp_apertura_cierre_auditoria();

--tg_recaudaciones_depositar_auditoria (Aduitoria de recaudaciones a depositar)-------------------------------------------------------------------------------
create or replace function sp_recaudaciones_depositar_auditoria() 
returns trigger
as $$
	begin
		-- Si la operacion es insertar un registro
	    if (TG_OP = 'INSERT') then
	        insert into recaudaciones_depositar_auditoria (
				recaudi_operacion,
				rec_cod,
				caj_cod,
				suc_cod,
				emp_cod,
				usu_cod,
				apcier_cod,
				rec_montoefec,
				rec_montocheq,
				rec_estado)
	        values (
				TG_OP,
				new.rec_cod,
				new.caj_cod,
				new.suc_cod,
				new.emp_cod,
				new.usu_cod,
				new.apcier_cod,
				new.rec_montoefec,
				new.rec_montocheq,
				new.rec_estado);
	    end if;
		-- Se retorna null para cerrar la funcion
	    return null; 
	end; 
$$
language plpgsql;

create trigger tg_recaudaciones_depositar_auditoria
after insert on recaudaciones_depositar
for each row execute function sp_recaudaciones_depositar_auditoria();

--tg_arqueo_control_auditoria (Aduitoria de arqueo de control)-------------------------------------------------------------------------------
create or replace function sp_arqueo_control_auditoria() 
returns trigger
as $$
	begin
		-- Si la operacion es insertar un registro
	    if (TG_OP = 'INSERT') then
	        insert into arqueo_control_auditoria (
				arqaudi_operacion,
				caj_cod,
				suc_cod,
				emp_cod,
				usu_cod,
				apcier_cod,
				arq_cod,
				arq_obs,
				arq_fecha,
				fun_cod)
	        values (
				TG_OP,
				new.caj_cod,
				new.suc_cod,
				new.emp_cod,
				new.usu_cod,
				new.apcier_cod,
				new.arq_cod,
				new.arq_obs,
				new.arq_fecha,
				new.fun_cod);
	    end if;
		-- Se retorna null para cerrar la funcion
	    return null; 
	end; 
$$
language plpgsql;

create trigger tg_arqueo_control_auditoria
after insert on arqueo_control
for each row execute function sp_arqueo_control_auditoria();

--tg_ventas_cab_auditoria (AUDITORIA DE VENTAS)------------------------------------------------------------------------------------
create or replace function sp_ventas_cab_auditoria() 
returns trigger
as $$
	declare 
		usu_cod_new integer;
		--se trae de cuentas a cobrar
		cuencob_saldo numeric := (select cuencob_saldo from cuentas_cobrar where ven_cod = new.ven_cod);
		tipo_comp integer := (select tipcomp_cod from cuentas_cobrar where ven_cod = new.ven_cod);
	begin
		if (cuencob_saldo = 0 and tipo_comp = 5) then
			usu_cod_new = (select distinct cc.usu_cod from cobros_cab cc 
							join cobros_det cd on cd.cobr_cod = cc.cobr_cod where cd.ven_cod = new.ven_cod and cc.cobr_estado = 'ACTIVO');
		elsif (cuencob_saldo = 0 and tipo_comp = 1) then
			usu_cod_new = (select usu_cod from nota_venta_cab 
							where ven_cod = new.ven_cod and tipcomp_cod = new.tipcomp_cod and notven_estado = 'ACTIVO');
		else 
			usu_cod_new = new.usu_cod;
		end if;
		-- Si la operacion es insertar o modificar un registro
	    if (TG_OP  in ('INSERT','UPDATE')) then
	        insert into ventas_cab_auditoria (
				vcaudi_operacion,
				ven_cod, 
				ven_fecha,
				ven_timbrado,
				ven_nrofac,
				ven_tipfac,
				ven_cuotas,
				ven_montocuota, 
				ven_intefecha,
				ven_estado,
				cli_cod,
				usu_cod,
				suc_cod,
				emp_cod,
				tipcomp_cod)
	        values (
				TG_OP,
				new.ven_cod, 
				new.ven_fecha,
				new.ven_timbrado,
				new.ven_nrofac,
				new.ven_tipfac,
				new.ven_cuotas,
				new.ven_montocuota, 
				new.ven_intefecha,
				new.ven_estado,
				new.cli_cod,
				usu_cod_new,
				new.suc_cod,
				new.emp_cod,
				new.tipcomp_cod);
	    end if;
		-- Se retorna null para cerrar la funcion
	    return null; 
	end; 
$$
language plpgsql;

create trigger tg_ventas_cab_auditoria
after insert or update on ventas_cab
for each row execute function sp_ventas_cab_auditoria();

--tg_venta_pedido_auditoria (AUDITORIA DE VENTA PEDIDO)-------------------------------------------------------------------------------
create or replace function sp_venta_pedido_auditoria()
returns trigger as 
$$
-- Se declaran los codigos y nombres de usuario para la tabla de auditoria
declare
	usu_cod_new integer := (select vc.usu_cod from ventas_cab vc where vc.ven_cod = new.ven_cod);
	usu_login_new varchar := (select u.usu_login from usuarios u where u.usu_cod = usu_cod_new);
begin
	if (TG_OP = 'INSERT') then
        insert into venta_pedido_auditoria (
			vpaudi_operacion,
			usu_cod, 
			usu_login, 
			pedven_cod, 
			ven_cod,
			venped_cod)
        values (
			TG_OP,
			usu_cod_new, 
			usu_login_new, 
			new.pedven_cod, 
			new.ven_cod, 
			new.venped_cod);
	end if;
	-- Se retorna null para cerrar la funcion
    return null; 
end;
$$
language plpgsql;

create trigger tg_venta_pedido_auditoria
after insert on venta_pedido
for each row execute function sp_venta_pedido_auditoria();

--tg_presupuesto_venta_auditoria (AUDITORIA DE PRESUPUESTO VENTA)--------------------------------------------------------------------
create or replace function sp_presupuesto_venta_auditoria()
returns trigger as 
$$
-- Se declaran los codigos y nombres de usuario para la tabla de auditoria
declare
	usu_cod_new integer := (select vc.usu_cod from ventas_cab vc where vc.ven_cod = new.ven_cod);
	usu_login_new varchar := (select u.usu_login from usuarios u where u.usu_cod = usu_cod_new);
begin
	if (TG_OP = 'INSERT') then
        insert into presupuesto_venta_auditoria (
			pvaudi_operacion,
			usu_cod, 
			usu_login, 
			preven_cod, 
			ven_cod,
			prpr_cod)
        values (
			TG_OP,
			usu_cod_new, 
			usu_login_new, 
			new.preven_cod, 
			new.ven_cod, 
			new.prpr_cod);
	end if;
	-- Se retorna null para cerrar la funcion
    return null; 
end;
$$
language plpgsql;

create trigger tg_presupuesto_venta_auditoria
after insert on presupuesto_venta
for each row execute function sp_presupuesto_venta_auditoria();

--tg_ventas_det_auditoria (AUDITORIA DE VENTAS DETALLE)------------------------------------------------------------------------
create or replace function sp_ventas_det_auditoria() 
returns trigger
as $$
	-- Se declaran los codigos y nombres de usuario para la tabla de auditoria
	declare
		usu_cod_old integer := (select vc.usu_cod from ventas_cab vc where vc.ven_cod = old.ven_cod);
		usu_login_old varchar := (select u.usu_login from usuarios u where u.usu_cod = usu_cod_old);
		usu_cod_new integer := (select vc.usu_cod from ventas_cab vc where vc.ven_cod = new.ven_cod);
		usu_login_new varchar := (select u.usu_login from usuarios u where u.usu_cod = usu_cod_new);
	begin
		-- Si la operacion es eliminar el registro
	    if (TG_OP = 'DELETE') then
	        insert into ventas_det_auditoria (
				vdaudi_operacion,
				usu_cod,
				usu_login,
				ven_cod, 
				itm_cod,
				tipitem_cod,
				dep_cod,
				suc_cod,
				emp_cod,
				vendet_cantidad, 
				vendet_precio)
	        values (
				TG_OP, 
				usu_cod_old, 
				usu_login_old,
				old.ven_cod,
				old.itm_cod,
				old.tipitem_cod,
				old.dep_cod,
				old.suc_cod,
				old.emp_cod,
				old.vendet_cantidad,
				old.vendet_precio);
		-- Si la operacion es insertar un registro
	    elseif (TG_OP = 'INSERT') then
	        insert into ventas_det_auditoria (
				vdaudi_operacion,
				usu_cod,
				usu_login,
				ven_cod, 
				itm_cod,
				tipitem_cod,
				dep_cod,
				suc_cod,
				emp_cod,
				vendet_cantidad, 
				vendet_precio)
	        values (
				TG_OP, 
				usu_cod_new, 
				usu_login_new,
				new.ven_cod,
				new.itm_cod,
				new.tipitem_cod,
				new.dep_cod,
				new.suc_cod,
				new.emp_cod,
				new.vendet_cantidad,
				new.vendet_precio);
	    end if;
		-- Se retorna null para cerrar la funcion
	    return null; 
	end; 
$$
language plpgsql;

create trigger tg_ventas_det_auditoria
after insert or delete on ventas_det
for each row execute function sp_ventas_det_auditoria();

--tg_libro_ventas_auditoria (AUDITORIA DE LIBRO DE VENAS)-----------------------------------------------------------------------------
create or replace function sp_libro_ventas_auditoria() 
returns trigger
as $$
	declare 
		usu_cod_new integer;
	begin
		if new.tipcomp_cod = 4 then
			usu_cod_new = (select usu_cod from ventas_cab where ven_cod = new.ven_cod);
		elsif new.tipcomp_cod in (1, 2) then
			usu_cod_new = (select usu_cod from nota_venta_cab 
							where ven_cod = new.ven_cod and tipcomp_cod = new.tipcomp_cod and notven_nronota = new.libven_nrocomprobante);
		end if;
		-- Si la operacion es insertar o modificar un registro
	    if (TG_OP  in ('INSERT','UPDATE')) then
	        insert into libro_ventas_auditoria (
				lvaudi_operacion,
				usu_cod,
				usu_login,
				ven_cod, 
				libven_cod,
				libven_fecha,
				libven_nrocomprobante,
				libven_excenta,
				libven_iva5,
				libven_iva10, 
				tipcomp_cod,
				libven_estado)
	        values (
				TG_OP,
				usu_cod_new,
				(select usu_login from usuarios where usu_cod = usu_cod_new),
				new.ven_cod, 
				new.libven_cod,
				new.libven_fecha,
				new.libven_nrocomprobante,
				new.libven_excenta,
				new.libven_iva5,
				new.libven_iva10, 
				new.tipcomp_cod,
				new.libven_estado);
	    end if;
		-- Se retorna null para cerrar la funcion
	    return null; 
	end; 
$$
language plpgsql;

create trigger tg_libro_ventas_auditoria
after insert or update on libro_ventas
for each row execute function sp_libro_ventas_auditoria();

--tg_cuentas_cobrar_auditoria (AUDITORIA DE CUENTAS A COBRAR)-------------------------------------------------------------------------------
create or replace function sp_cuentas_cobrar_auditoria() 
returns trigger
as $$
	declare 
		usu_cod_new integer;
	begin
		if new.tipcomp_cod = 4 then
			usu_cod_new = (select usu_cod from ventas_cab where ven_cod = new.ven_cod);
		elsif new.tipcomp_cod = 5 then
			usu_cod_new = (select distinct cc.usu_cod from cobros_cab cc 
							join cobros_det cd on cd.cobr_cod = cc.cobr_cod where cd.ven_cod = new.ven_cod and cc.cobr_estado = 'ACTIVO');
		elsif new.tipcomp_cod in (1, 2) then
			usu_cod_new = (select usu_cod from nota_venta_cab 
							where ven_cod = new.ven_cod and tipcomp_cod = new.tipcomp_cod and notven_estado = 'ACTIVO');
		end if;
		-- Si la operacion es insertar o modificar un registro
	    if (TG_OP  in ('INSERT','UPDATE')) then
	        insert into cuentas_cobrar_auditoria (
				ccaudi_operacion,
				usu_cod,
				usu_login,
				ven_cod, 
				cuencob_cuotas,
				cuencob_monto,
				cuencob_saldo,
				tipcomp_cod,
				cuencob_estado)
	        values (
				TG_OP,
				usu_cod_new,
				(select usu_login from usuarios where usu_cod = usu_cod_new),
				new.ven_cod, 
				new.cuencob_cuotas,
				new.cuencob_monto,
				new.cuencob_saldo, 
				new.tipcomp_cod,
				new.cuencob_estado);
	    end if;
		-- Se retorna null para cerrar la funcion
	    return null; 
	end; 
$$
language plpgsql;

create trigger tg_cuentas_cobrar_auditoria
after insert or update on cuentas_cobrar
for each row execute function sp_cuentas_cobrar_auditoria();
