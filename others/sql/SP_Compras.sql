-----------------------------------------------------------REFERENCIALES-----------------------------------------------------------

--sp_abm_empresa (EMPRESA)
CREATE OR REPLACE FUNCTION sp_abm_empresa
(empcod integer, 
emprazonsocial varchar, 
empruc varchar,
emptelefono varchar,
empemail varchar,
empactividad varchar,
empestado varchar,
emptimbrado varchar,
operacion integer,
usucod integer,
usulogin varchar,
transaccion varchar)
RETURNS void
AS $$
declare empaudit text;
begin --iniciar
	--se designan validaciones
	if operacion in (1,2) then
		perform * from empresa
		where (emp_razonsocial = upper(emprazonsocial) or emp_ruc = empruc) and emp_cod != empcod;
		if found then
			raise exception '1';
		elseif operacion = 1 then --realizamos un insert
			INSERT INTO public.empresa
			(emp_cod, 
			emp_razonsocial, 
			emp_ruc,
			emp_telefono,
			emp_email,
			emp_actividad,
			emp_estado,
			emp_timbrado)
			VALUES(
			empcod, 
			upper(emprazonsocial),
			empruc,
			emptelefono,
			empemail,
			upper(empactividad),
			'ACTIVO',
			emptimbrado);
			raise notice 'LA EMPRESA FUE REGISTRADA CON EXITO';
		elseif operacion = 2 then -- realizamos un update 
			UPDATE public.empresa 
			SET emp_razonsocial = upper(emprazonsocial), 
			emp_ruc = empruc,
			emp_telefono = emptelefono,
			emp_email = empemail,
			emp_actividad = upper(empactividad),
			emp_estado ='ACTIVO',
			emp_timbrado = emptimbrado
			WHERE emp_cod = empcod;
			raise notice 'LA EMPRESA FUE MODIFICADA CON EXITO';
		end if;
	end if;
	if operacion = 3 then -- realizamos un update 
		update empresa
		set emp_estado = 'INACTIVO'
		WHERE emp_cod = empcod ;
		raise notice 'LA EMPRESA FUE BORRADA CON EXITO';
	end if;
	--se selecciona la ultima auditoria
	select coalesce(emp_audit,'') into empaudit
	from empresa
	where emp_cod = empcod;
	
	--se actualiza la auditoria
	update empresa 
    set emp_audit = empaudit||' '||json_build_object(
        'usu_cod', usucod,
		'usu_login', usulogin,
        'fecha y hora', to_char(current_timestamp,'dd-mm-yyyy hh24:mi:ss'),
        'transaccion', upper(transaccion),
		'emp_razonsocial', upper(emprazonsocial),
		'emp_ruc', empruc,
		'emp_timbrado', emptimbrado,
		'emp_telefono', emptelefono, 
		'emp_email', empemail,
		'emp_actividad', upper(empactividad),
		'emp_estado', upper(empestado)
    )||','
    WHERE emp_cod = empcod;
end--finalizar
$$
language plpgsql;



select sp_abm_empresa (2,'Netflix','80554-8','021-855-7875','netflix@latam.com','streaming','',1,'mcentu','INSERCION');

--sp_abm_sucursales (SUCURSALES)
CREATE OR REPLACE FUNCTION sp_abm_sucursales
(succod integer, 
empcod integer, 
sucdescri varchar,
suctelefono varchar,
sucdireccion varchar,
sucestado varchar,
ciucod integer,
sucemail varchar,
operacion integer,
usucod integer,
usulogin varchar,
transaccion varchar,
emprazonsocial varchar,
ciudescripcion varchar)
RETURNS void
AS $$
declare sucaudit text;
begin --iniciar
	--se designan validaciones
	if operacion in (1,2) then
		perform * from sucursales
		where (emp_cod = empcod and suc_descri = upper(sucdescri)) and suc_cod != succod;
		if found then
			raise exception '1';
		elseif operacion = 1 then --realizamos un insert
			INSERT INTO public.sucursales
			(suc_cod, 
			emp_cod,
			ciu_cod,
			suc_descri,
			suc_telefono,
			suc_direccion,
			suc_estado,
			suc_email)
			VALUES(
			succod, 
			empcod,
			ciucod,
			upper(sucdescri),
			suctelefono,
			upper(sucdireccion),
			'ACTIVO',
			sucemail);
			raise notice 'LA SUCURSAL FUE REGISTRADA CON EXITO';
		elseif operacion = 2 then -- realizamos un update 
			UPDATE public.sucursales
			SET emp_cod = empcod,
			ciu_cod = ciucod,
			suc_descri = upper(sucdescri),
			suc_telefono = suctelefono,
			suc_direccion = upper(sucdireccion),
			suc_estado = 'ACTIVO',
			suc_email = sucemail
			WHERE suc_cod = succod;
			raise notice 'LA SUCURSAL FUE MODIFICADA CON EXITO';
		end if;
	end if;
	if operacion = 3 then -- realizamos un update 
		update sucursales 
		set suc_estado = 'INACTIVO'
		WHERE suc_cod = succod;
		raise notice 'LA SUCURSAL FUE BORRADA CON EXITO';
	end if;
	--se selecciona la ultima auditoria
	select coalesce(suc_audit,'') into sucaudit
	from sucursales
	where suc_cod = succod;
	
	--se actualiza la auditoria
	update sucursales 
    set suc_audit = sucaudit||' '||json_build_object(
        'usu_cod', usucod,
		'usu_login', usulogin,
        'fecha y hora', to_char(current_timestamp,'dd-mm-yyyy hh24:mi:ss'),
        'transaccion', upper(transaccion),
		'emp_cod', empcod,
        'emp_razonsocial', upper(emprazonsocial),
		'suc_descri', upper(sucdescri),
		'suc_telefono', suctelefono, 
		'ciu_cod', ciucod,
		'ciu_descripcion', upper(ciudescripcion),
		'suc_direccion', upper(sucdireccion),
		'suc_email', sucemail,
		'suc_estado', upper(sucestado)
    )||','
    WHERE suc_cod = succod;
end--finalizar
$$
language plpgsql;

CREATE OR REPLACE FUNCTION sp_abm_ciudades
(ciucod integer, 
ciudescripcion varchar, 
ciuestado varchar,
operacion integer,
usucod integer,
usulogin varchar,
transaccion varchar)
RETURNS void
AS $$
declare ciuaudit text;
begin --iniciar
	--se designan validaciones
	if operacion in (1,2) then
		perform * from ciudad
		where ciu_descripcion = upper(ciudescripcion) and ciu_cod != ciucod;
		if found then
			raise exception '1';
		elseif operacion = 1 then --realizamos un insert
			INSERT INTO public.ciudad
			(ciu_cod, 
			ciu_descripcion, 
			ciu_estado)
			VALUES(
			ciucod, 
			upper(ciudescripcion), 
			'ACTIVO');
			raise notice 'LA CIUDAD FUE REGISTRADA CON EXITO';
		elseif operacion = 2 then -- realizamos un update 
			UPDATE public.ciudad
			SET ciu_descripcion = upper(ciudescripcion), 
			ciu_estado ='ACTIVO'
			WHERE ciu_cod = ciucod;
			raise notice 'LA CIUDAD FUE MODIFICADA CON EXITO';
		end if;
	end if;
	if operacion = 3 then -- realizamos un update 
		update ciudad
		set ciu_estado = 'INACTIVO'
		WHERE ciu_cod = ciucod;
		raise notice 'LA CIUDAD FUE BORRADA CON EXITO';
	end if;
	--se selecciona la ultima auditoria
	select coalesce(ciu_audit,'') into ciuaudit
	from ciudad
	where ciu_cod = ciucod;
	
	--se actualiza la auditoria
	update ciudad 
    set ciu_audit = ciuaudit||' '||json_build_object(
        'usu_cod', usucod,
		'usu_login', usulogin,
        'fecha y hora', to_char(current_timestamp,'dd-mm-yyyy hh24:mi:ss'),
        'transaccion', upper(transaccion),
		'ciu_descripcion', upper(ciudescripcion), 
		'ciu_audit', upper(ciuestado)
    )||','
    WHERE ciu_cod = ciucod;
end--finalizar
$$
language plpgsql;

--sp_abm_tipoImpuesto (TIPO IMPUESTO)
CREATE OR REPLACE FUNCTION sp_abm_tipoImpuesto
(tipimpcod integer, 
tipimpdescri varchar, 
tipimpestado varchar,
operacion integer,
usucod integer,
usulogin varchar,
transaccion varchar)
RETURNS void
AS $$
declare tipimpaudit text;
begin --iniciar
	--se designan validaciones
	if operacion in (1,2) then
		perform * from tipo_impuesto
		where tipimp_descri = upper(tipimpdescri) and tipimp_cod != tipimpcod;
		if found then
			raise exception '1';
		elseif operacion = 1 then --realizamos un insert
			INSERT INTO tipo_impuesto
			(tipimp_cod, 
			tipimp_descri, 
			tipimp_estado)
			VALUES(
			tipimpcod, 
			upper(tipimpdescri), 
			'ACTIVO');
			raise notice 'EL TIPO DE IMPUESTO FUE REGISTRADO CON EXITO';
		elseif operacion = 2 then -- realizamos un update 
			UPDATE tipo_impuesto 
			SET tipimp_descri = upper(tipimpdescri), 
			tipimp_estado ='ACTIVO'
			WHERE tipimp_cod = tipimpcod;
			raise notice 'EL TIPO DE IMPUESTO FUE MODIFICADO CON EXITO';
		end if;
	end if;
	if operacion = 3 then -- realizamos un update 
		update tipo_impuesto
		set tipimp_estado = 'INACTIVO'
		WHERE tipimp_cod = tipimpcod ;
		raise notice 'EL TIPO DE IMPUESTO FUE BORRADO CON EXITO';
	end if;
	--se selecciona la ultima auditoria
	select coalesce(tipimp_audit,'') into tipimpaudit
	from tipo_impuesto
	where tipimp_cod = tipimpcod;

	--se actualiza la auditoria
	update tipo_impuesto 
    set tipimp_audit = tipimpaudit||' '||json_build_object(
        'usu_cod', usucod,
		'usu_login', usulogin,
        'fecha y hora', to_char(current_timestamp,'dd-mm-yyyy hh24:mi:ss'),
        'transaccion', upper(transaccion),
		'tipimp_descri', upper(tipimpdescri), 
		'tipimp_estado', upper(tipimpestado)
    )||','
    WHERE tipimp_cod = tipimpcod;
end--finalizar
$$
language plpgsql;

--sp_abm_tipoItem (TIPO ITEM)
CREATE OR REPLACE FUNCTION sp_abm_tipoItem
(tipitemcod integer, 
tipitemdescri varchar, 
tipitemestado varchar,
operacion integer,
usucod integer,
usulogin varchar,
transaccion varchar)
RETURNS void
AS $$
declare tipitemaudit text;
begin --iniciar
	--se designan validaciones
	if operacion in (1,2) then
		perform * from tipo_item
		where tipitem_descri = upper(tipitemdescri) and tipitem_cod != tipitemcod;
		if found then
			raise exception '1';
		elseif operacion = 1 then --realizamos un insert
			INSERT INTO tipo_item
			(tipitem_cod, 
			tipitem_descri, 
			tipitem_estado)
			VALUES(
			tipitemcod, 
			upper(tipitemdescri), 
			'ACTIVO');
			raise notice 'EL TIPO DE ITEM FUE REGISTRADO CON EXITO';
		elseif operacion = 2 then -- realizamos un update 
			UPDATE tipo_item 
			SET tipitem_descri = upper(tipitemdescri), 
			tipitem_estado ='ACTIVO'
			WHERE tipitem_cod = tipitemcod;
			raise notice 'EL TIPO DE ITEM FUE MODIFICADO CON EXITO';
		end if;
	end if;
	if operacion = 3 then -- realizamos un update 
		update tipo_item
		set tipitem_estado = 'INACTIVO'
		WHERE tipitem_cod = tipitemcod ;
		raise notice 'EL TIPO DE ITEM FUE BORRADO CON EXITO';
	end if;
	--se selecciona la ultima auditoria
	select coalesce(tipitem_audit,'') into tipitemaudit
	from tipo_item
	where tipitem_cod = tipitemcod;

	--se actualiza la auditoria
	update tipo_item 
    set tipitem_audit = tipitemaudit||' '||json_build_object(
        'usu_cod', usucod,
		'usu_login', usulogin,
        'fecha y hora', to_char(current_timestamp,'dd-mm-yyyy hh24:mi:ss'),
        'transaccion', upper(transaccion),
		'tipitem_descri', upper(tipitemdescri), 
		'tipitem_estado', upper(tipitemestado)
    )||','
    WHERE tipitem_cod = tipitemcod;
end--finalizar
$$
language plpgsql;

--sp_abm_tipoProveedor (TIPO PROVEEDOR)
CREATE OR REPLACE FUNCTION sp_abm_tipoProveedor
(tiprovcod integer, 
tiprovdescricion varchar, 
tiprovestado varchar,
operacion integer,
usucod integer,
usulogin varchar,
transaccion varchar)
RETURNS void
AS $$
declare tiprovaudit text;
begin --iniciar
	--se designan validaciones
	if operacion in (1,2) then
		perform * from tipo_proveedor
		where tiprov_descripcion = upper(tiprovdescricion) and tiprov_cod != tiprovcod;
		if found then
			raise exception '1';
		elseif operacion = 1 then --realizamos un insert
			INSERT INTO tipo_proveedor
			(tiprov_cod, 
			tiprov_descripcion, 
			tiprov_estado)
			VALUES(
			tiprovcod, 
			upper(tiprovdescricion), 
			'ACTIVO');
			raise notice 'EL TIPO DE PROVEEDOR FUE REGISTRADO CON EXITO';
		elseif operacion = 2 then -- realizamos un update 
			UPDATE tipo_proveedor 
			SET tiprov_descripcion = upper(tiprovdescricion), 
			tiprov_estado ='ACTIVO'
			WHERE tiprov_cod = tiprovcod;
			raise notice 'EL TIPO DE PROVEEDOR FUE MODIFICADO CON EXITO';
		end if;
	end if;
	if operacion = 3 then -- realizamos un update 
		update tipo_proveedor
		set tiprov_estado = 'INACTIVO'
		WHERE tiprov_cod = tiprovcod ;
		raise notice 'EL TIPO DE PROVEEDOR FUE BORRADO CON EXITO';
	end if;
	--se selecciona la ultima auditoria
	select coalesce(tiprov_audit,'') into tiprovaudit
	from tipo_proveedor
	where tiprov_cod = tiprovcod;

	--se actualiza la auditoria
	update tipo_proveedor
    set tiprov_audit = tiprovaudit||' '||json_build_object(
        'usu_cod', usucod,
		'usu_login', usulogin,
        'fecha y hora', to_char(current_timestamp,'dd-mm-yyyy hh24:mi:ss'),
        'transaccion', upper(transaccion),
		'tiprov_descripcion', upper(tiprovdescricion), 
		'tiprov_estado', upper(tiprovestado)
    )||','
    WHERE tiprov_cod = tiprovcod;
end--finalizar
$$
language plpgsql;

--sp_abm_proveedores (PROVEEDORES)
CREATE OR REPLACE FUNCTION sp_abm_proveedores
(procod integer, 
tiprovcod integer, 
prorazonsocial varchar,
proruc varchar,
prodireccion varchar,
protelefono varchar,
proemail varchar,
proestado varchar,
protimbrado varchar,
operacion integer,
usucod integer,
usulogin varchar,
transaccion varchar,
tiprovdescripcion varchar)
RETURNS void
AS $$
declare proaudit text;
begin --iniciar
	--se designan validaciones
	if operacion in (1,2) then
		perform * from proveedor
		where (pro_razonsocial = upper(prorazonsocial) or pro_ruc = proruc) and pro_cod != procod;
		if found then
			raise exception '1';
		elseif operacion = 1 then --realizamos un insert
			INSERT INTO proveedor
			(pro_cod, 
			tiprov_cod,
			pro_razonsocial, 
			pro_ruc,
			pro_direccion,
			pro_telefono,
			pro_email,
			pro_estado,
			pro_timbrado)
			VALUES(
			procod, 
			tiprovcod,
			upper(prorazonsocial),
			proruc,
			upper(prodireccion),
			protelefono,
			proemail,
			'ACTIVO',
			protimbrado);
			raise notice 'EL PROVEEDOR FUE REGISTRADO CON EXITO';
		elseif operacion = 2 then -- realizamos un update 
			UPDATE proveedor
			SET tiprov_cod = tiprovcod,
			pro_razonsocial = upper(prorazonsocial), 
			pro_ruc = proruc,
			pro_direccion = upper(prodireccion),
			pro_telefono = protelefono,
			pro_email = proemail,
			pro_estado ='ACTIVO',
			pro_timbrado = protimbrado
			WHERE pro_cod = procod;
			raise notice 'EL PROVEEDOR FUE MODIFICADO CON EXITO';
		end if;
	end if;
	if operacion = 3 then -- realizamos un update 
		update proveedor
		set pro_estado = 'INACTIVO'
		WHERE pro_cod = procod ;
		raise notice 'EL PROVEEDOR FUE BORRADO CON EXITO';
	end if;
	--se selecciona la ultima auditoria
	select coalesce(pro_audit,'') into proaudit
	from proveedor
	where pro_cod = procod;
	
	--se actualiza la auditoria
	update proveedor 
    set pro_audit = proaudit||' '||json_build_object(
        'usu_cod', usucod,
		'usu_login', usulogin,
        'fecha y hora', to_char(current_timestamp,'dd-mm-yyyy hh24:mi:ss'),
        'transaccion', upper(transaccion),
		'tiprov_cod', tiprovcod,
		'tiprov_descripcion', upper(tiprovdescripcion),
		'pro_razonsocial', upper(prorazonsocial),
		'pro_ruc', proruc,
		'pro_timbrado', protimbrado,
		'pro_direccion', upper(prodireccion),
		'pro_telefono', protelefono, 
		'pro_email', proemail,
		'pro_estado', upper(proestado)
    )||','
    WHERE pro_cod = procod;
end--finalizar
$$
language plpgsql;

select sp_abm_proveedores (1,2,'prueba s.a.','741258-9','acceso sur','021-478-456','@@@@74@@@','',2,'mcentu','modificacion','mayorista');

--sp_abm_depositos (DEPOSITOS)
CREATE OR REPLACE FUNCTION sp_abm_depositos
(depcod integer, 
succod integer,
empcod integer,
ciucod integer,
depdescri varchar, 
depestado varchar,
operacion integer,
usucod integer,
usulogin varchar,
transaccion varchar,
ciudescripcion varchar,
emprazonsocial varchar,
sucdescri varchar)
RETURNS void
AS $$
declare depaudit text;
begin --iniciar
	--se designan validaciones
	if operacion in (1,2) then
		perform * from depositos
		where (suc_cod = succod and emp_cod = empcod and dep_descri = upper(depdescri)) and dep_cod != depcod;
		if found then
			raise exception '1';
		elseif operacion = 1 then --realizamos un insert
			INSERT INTO depositos
			(dep_cod,
			suc_cod,
			emp_cod,
			ciu_cod,
			dep_descri, 
			dep_estado)
			VALUES(
			depcod,
			succod,
			empcod,
			ciucod,
			upper(depdescri),
			'ACTIVO');
			raise notice 'EL DEPÓSITO FUE REGISTRADO CON EXITO';
		elseif operacion = 2 then -- realizamos un update 
			UPDATE depositos 
			SET suc_cod = succod,
			emp_cod = empcod,
			ciu_cod = ciucod,
			dep_descri = upper(depdescri),
			dep_estado ='ACTIVO'
			WHERE dep_cod = depcod;
			raise notice 'EL DEPOSITO FUE MODIFICADO CON EXITO';
		end if;
	end if;
	if operacion = 3 then -- realizamos un update 
		update depositos
		set dep_estado = 'INACTIVO'
		WHERE dep_cod = depcod;
		raise notice 'EL DEPOSITO FUE BORRADO CON EXITO';
	end if;
	--se selecciona la ultima auditoria
	select coalesce(dep_audit,'') into depaudit
	from depositos
	where dep_cod = depcod;
	
	--se actualiza la auditoria
	update depositos 
    set dep_audit = depaudit||' '||json_build_object(
        'usu_cod', usucod,
		'usu_login', usulogin,
        'fecha y hora', to_char(current_timestamp,'dd-mm-yyyy hh24:mi:ss'),
        'transaccion', upper(transaccion),
		'dep_descri', upper(depdescri),
        'ciu_cod', ciucod,
		'ciu_descripcion', upper(ciudescripcion),
        'emp_cod', empcod,
		'emp_razonsocial', upper(emprazonsocial),
        'suc_cod', succod, 
		'suc_descri', upper(sucdescri),
		'dep_estado', upper(depestado)
    )||','
    WHERE dep_cod = depcod;
end--finalizar
$$
language plpgsql;

--sp_abm_items (ITEMS)
CREATE OR REPLACE FUNCTION sp_abm_items
(itmcod integer, 
tipitemcod integer, 
itmdescri varchar,
itmcosto numeric,
itmprecio numeric,
itmestado varchar,
tipimpcod integer,
unicod integer,
operacion integer,
usucod integer,
usulogin varchar,
transaccion varchar,
unidescri varchar,
tipitemdescri varchar,
tipimpdescri varchar)
RETURNS void
AS $$
declare itmaudit text;
begin --iniciar
	--se designan validaciones
	if operacion in (1,2) then
		perform * from items
		where itm_descri = upper(itmdescri) and itm_cod != itmcod;
		if found then
			raise exception '1';
		elseif operacion = 1 then --realizamos un insert
			INSERT INTO items
			(itm_cod, 
			tipitem_cod, 
			itm_descri,
			itm_costo,
			itm_precio,
			itm_estado,
			tipimp_cod,
			uni_cod)
			VALUES(
			itmcod, 
			tipitemcod,
			upper(itmdescri), 
			itmcosto,
			itmprecio,
			'ACTIVO',
			tipimpcod,
			unicod);
			raise notice 'EL ITEM FUE REGISTRADO CON EXITO';
		elseif operacion = 2 then -- realizamos un update 
			UPDATE items
			SET tipitem_cod = tipitemcod,
			itm_descri = upper(itmdescri), 
			itm_costo = itmcosto,
			itm_precio = itmprecio,
			itm_estado = 'ACTIVO',
			tipimp_cod = tipimpcod,
			uni_cod = unicod
			WHERE itm_cod = itmcod;
			raise notice 'EL ITEM FUE MODIFICADO CON EXITO';
		end if;
	end if;
	if operacion = 3 then -- realizamos un update 
		update items 
		set itm_estado = 'INACTIVO'
		WHERE itm_cod = itmcod ;
		raise notice 'EL ITEM FUE BORRADO CON EXITO';
	end if;
	--se selecciona la ultima auditoria
	select coalesce(itm_audit,'') into itmaudit
	from items
	where itm_cod = itmcod;
	
	--se actualiza la auditoria
	update items 
    set itm_audit = itmaudit||' '||json_build_object(
        'usu_cod', usucod,
		'usu_login', usulogin,
        'fecha y hora', to_char(current_timestamp,'dd-mm-yyyy hh24:mi:ss'),
        'transaccion', upper(transaccion),
		'itm_descri', upper(itmdescri),
		'itm_costo', itmcosto,
		'itm_precio', itmprecio,
        'uni_cod', unicod,
		'uni_descri', upper(unidescri),
        'tipitem_cod', tipitemcod,
		'tipitem_descri', upper(tipitemdescri), 
        'tipimp_cod', tipimpcod,
		'tipimp_descri', upper(tipimpdescri),
		'itm_estado', upper(itmestado)
    )||','
    WHERE itm_cod = itmcod;
end--finalizar
$$
language plpgsql;

select sp_abm_items (1,1,'musculosa',50000,70000,'',1,1,2,'mcentu','modificacion','unidades','prenda','iva 10%');

-----------------------------------------------------------MOVIMIENTOS-----------------------------------------------------------

--sp_pedido_compra_cab (PEDIDO COMPRA CABECERA)
CREATE OR REPLACE FUNCTION sp_pedido_compra_cab
(pedcomcod integer,
usucod integer,
succod integer,
empcod integer,
pedcomestado varchar,
operacion_cab integer,
usulogin varchar,
sucdescri varchar,
emprazonsocial varchar,
transaccion varchar)
RETURNS void
AS $$
declare pedcomaudit text;
begin 
    if operacion_cab = 1 then
        -- aqui hacemos un insert
        INSERT INTO pedido_compra_cab 
        (pedcom_cod,
        pedcom_fecha, 
        usu_cod, 
        suc_cod,
        emp_cod,
        pedcom_estado)
        VALUES(
        pedcomcod,
        current_date,
        usucod,
        succod,
      	empcod,
      	'ACTIVO');
        raise notice 'EL PEDIDO FUE REGISTADO CON EXITO';
    end if;
    if operacion_cab = 2 then
        -- aqui hacemos un update
		update pedido_compra_cab 
			SET pedcom_estado = 'ANULADO'
			usu_cod = usucod
        WHERE pedcom_cod = pedcomcod;
        raise notice 'EL PEDIDO FUE ANULADO';
    end if;
	--se selecciona la ultima auditoria
	select coalesce(pedcom_audit,'') into pedcomaudit
	from pedido_compra_cab
	where pedcom_cod = pedcomcod;

	--se actualiza la auditoria
	update pedido_compra_cab
    set pedcom_audit = pedcomaudit||' '||json_build_object(
        'usu_cod', usucod,
		'usu_login', usulogin,
        'fecha y hora', to_char(current_timestamp,'dd-mm-yyyy hh24:mi:ss'),
        'transaccion', upper(transaccion),
        'emp_cod', empcod,
        'emp_razonsocial', upper(emprazonsocial),
        'suc_cod', succod,
		'suc_descri', upper(sucdescri), 
		'pedcom_estado', upper(pedcomestado)
    )||','
    WHERE pedcom_cod = pedcomcod;
end
$$
language plpgsql;


--sp_pedido_compra_det (PEDIDO COMPRA DETALLE)
CREATE OR REPLACE FUNCTION sp_pedido_compra_det
(itmcod integer, 
tipitemcod integer, 
pedcomcod integer, 
pedcomdetcantidad numeric, 
pedcomdetprecio numeric, 
operacion integer)
RETURNS void
AS $$
begin 
	if operacion = 1 then
		perform * from pedido_compra_det
		where itm_cod = itmcod and pedcom_cod = pedcomcod;
		if found then
			raise exception '1';
	    elseif operacion = 1 then
	        -- aqui hacemos un insert
	        INSERT INTO pedido_compra_det 
				(itm_cod,
				tipitem_cod, 
				pedcom_cod, 
				pedcomdet_cantidad,
				pedcomdet_precio)
	        VALUES(
				itmcod,
				tipitemcod,
				pedcomcod,
				pedcomdetcantidad,
				pedcomdetprecio);
	        raise notice 'EL DETALLE FUE REGISTADO CON EXITO';
	    end if;
	end if;
    if operacion = 2 then
    	-- aqui hacemos un delete
		delete from pedido_compra_det 
		where 
		itm_cod = itmcod 
		and tipitem_cod = tipitemcod 
		and pedcom_cod = pedcomcod;
		raise notice 'EL DETALLE FUE ELIMINADO CON ÉXITO';
	end if;
end
$$
language plpgsql;

--sp_presupuesto_prov_cab (PRESUPUESTO PROVEEDOR CABECERA)
CREATE OR REPLACE FUNCTION sp_presupuesto_prov_cab
(presprovcod integer,
presprovfecha date,
presprovfechavenci date,
presprovestado varchar,
procod integer,
tiprovcod integer,
succod integer,
empcod integer,
usucod integer,
pedcomcod integer,
operacion integer,
proruc varchar,
prorazonsocial varchar,
sucdescri varchar,
emprazonsocial varchar,
usulogin varchar,
transaccion varchar)
RETURNS void
AS $$
declare presprovaudit text;
declare	pedprecod integer := (select coalesce (max(pedpre_cod),0)+1 from pedido_presupuesto);
begin 
    if operacion = 1 then
		if current_date > presprovfechavenci then
			raise exception 'fecha';
		end if;
		perform 
		ppc.presprov_cod,
		pp.pedcom_cod,
		ppc.pro_cod,
		ppc.presprov_fecha,
		ppc.presprov_fechavenci 
		from presupuesto_prov_cab ppc 
		join pedido_presupuesto pp on pp.presprov_cod = ppc.presprov_cod
		where (pp.pedcom_cod = pedcomcod and ppc.pro_cod = procod and ppc.presprov_cod != presprovcod);
		if found then
			raise exception 'pedido';
	    elseif operacion = 1 then
        -- aqui hacemos un insert
	        INSERT INTO presupuesto_prov_cab 
				(presprov_cod,
				presprov_fecha, 
				presprov_fechavenci,
				presprov_estado,
				pro_cod,
				tiprov_cod,
				suc_cod,
				emp_cod,
				usu_cod)
			VALUES(
				presprovcod,
				presprovfecha,
				presprovfechavenci,
				'ACTIVO',
				procod,
				tiprovcod,
				succod,
				empcod,
				usucod);
		    INSERT INTO pedido_presupuesto
				(pedpre_cod,
				pedcom_cod,
				presprov_cod)
				values(
				pedprecod,
				pedcomcod,
				presprovcod);
			--se actualiza la auditoria
			update pedido_presupuesto
				set pedpre_audit = json_build_object(
					'usu_cod', usucod,
					'usu_login', usulogin,
					'fecha y hora', to_char(current_timestamp,'dd-mm-yyyy hh24:mi:ss'),
					'transaccion', upper(transaccion),
					'pedcom_cod', pedcomcod,
					'presprov_cod', presprovcod
				)
		    WHERE pedpre_cod = pedprecod;
	    	raise notice 'EL PRESUPUESTO FUE REGISTADO CON EXITO';
	  	end if;
    end if;
    if operacion = 2 then
        -- aqui hacemos un update
		update presupuesto_prov_cab 
			SET presprov_estado = 'ANULADO',
			usu_cod = usucod
        WHERE presprov_cod = presprovcod;
        raise notice 'EL PRESUPUESTO FUE ANULADO';
    end if;
	--se selecciona la ultima auditoria
	select coalesce(presprov_audit,'') into presprovaudit
	from presupuesto_prov_cab
	where presprov_cod = presprovcod;

	--se actualiza la auditoria
	update presupuesto_prov_cab
    set presprov_audit = presprovaudit||' '||json_build_object(
        'usu_cod', usucod,
		'usu_login', usulogin,
        'fecha y hora', to_char(current_timestamp,'dd-mm-yyyy hh24:mi:ss'),
        'transaccion', upper(transaccion),
        'presprov_fecha', to_char(presprovfecha,'dd-mm-yyyy'),
        'presprov_fechavenci', to_char(presprovfechavenci,'dd-mm-yyyy'),
        'tiprov_cod', tiprovcod,
        'pro_cod', procod,
        'pro_ruc', proruc,
        'pro_razonsocial', upper(prorazonsocial),
        'emp_cod', empcod,
        'emp_razonsocial', upper(emprazonsocial),
        'suc_cod', succod,
		'suc_descri', upper(sucdescri), 
		'presprov_estado', upper(presprovestado)
    )||','
    WHERE presprov_cod = presprovcod;
end
$$
language plpgsql;


--sp_presupuesto_prov_det (PRESUPUESTO PROVEEDOR DETALLE)
CREATE OR REPLACE FUNCTION sp_presupuesto_prov_det
(itmcod integer, 
tipitemcod integer, 
presprovcod integer, 
presprovdetcantidad numeric, 
presprovdetprecio numeric, 
operacion integer)
RETURNS void
AS $$
begin 
	if operacion = 1 then
		perform * from presupuesto_prov_det
		where itm_cod = itmcod and presprov_cod = presprovcod;
		if found then
			raise exception '1';
	    elseif operacion = 1 then
	        -- aqui hacemos un insert
	        INSERT INTO presupuesto_prov_det 
	        (itm_cod,
	        tipitem_cod, 
	        presprov_cod, 
	        presprovdet_cantidad,
	        presprovdet_precio)
	        VALUES(
	        itmcod,
	        tipitemcod,
	        presprovcod,
	        presprovdetcantidad,
	        presprovdetprecio);
	        raise notice 'EL DETALLE FUE REGISTADO CON EXITO';
	    end if;
	end if;
    if operacion = 2 then
    	-- aqui hacemos un delete
		delete from presupuesto_prov_det 
		where 
		itm_cod = itmcod 
		and tipitem_cod = tipitemcod 
		and presprov_cod = presprovcod;
		raise notice 'EL DETALLE FUE ELIMINADO CON ÉXITO';
	end if;
end
$$
language plpgsql;

--sp_orden_compra_cab (ORDEN COMPRA CABECERA)
CREATE OR REPLACE FUNCTION sp_orden_compra_cab
(ordcomcod integer,
ordcomfecha date,
ordcomcondicionpago formpago,
ordcomcuota integer,
ordcomintefecha varchar,
ordcomestado varchar,
procod integer,
tiprovcod integer,
succod integer,
empcod integer,
usucod integer,
ordcommontocuota numeric,
presprovcod integer,
pedcomcod integer,
operacion integer,
prorazonsocial varchar,
sucdescri varchar,
emprazonsocial varchar,
usulogin varchar,
transaccion varchar)
RETURNS void
AS $$
declare 
	ordcomaudit text;
	preorcod integer := (select coalesce (max(preor_cod),0)+1 from presupuesto_orden);
	cu_presupuesto cursor is
		select 
			p.pro_ruc as proruc,
			coalesce(ppc.presprov_audit,'') as presprovaudit,
			to_char(ppc.presprov_fecha,'dd-mm-yyyy') as presprovfecha,
			to_char(ppc.presprov_fechavenci,'dd-mm-yyyy') as presprovfechavenci,
			ppc.presprov_estado as presprovestado
		from presupuesto_prov_cab ppc
			join proveedor p on p.pro_cod = ppc.pro_cod
		where ppc.presprov_cod = presprovcod;
	cu_pedido cursor is
		select 
			coalesce(pedcom_audit,'') as pedcomaudit,
			pedcom_estado as pedcomestado
		from pedido_compra_cab
		where pedcom_cod = pedcomcod;
begin 
    if operacion = 1 then
        -- aqui hacemos un insert
	        INSERT INTO orden_compra_cab 
		        (ordcom_cod,
		        ordcom_fecha, 
		        ordcom_condicionpago,
		        ordcom_cuota,
		        ordcom_intefecha,
		        ordcom_estado,
		        pro_cod,
		        tiprov_cod,
		        suc_cod,
		        emp_cod,
		        usu_cod,
				ordcom_montocuota)
	        VALUES(
		        ordcomcod,
		        ordcomfecha,
		        ordcomcondicionpago,
		        ordcomcuota,
		        upper(ordcomintefecha),
		      	'ACTIVO',
		      	procod,
		      	tiprovcod,
		        succod,
		      	empcod,
		    	usucod,
				ordcommontocuota);
	    --INSERTA DATOS EN presupuesto_orden
		    INSERT INTO presupuesto_orden
			    (preor_cod,
			    presprov_cod,
			    ordcom_cod)
		    values(
			    preorcod,
			    presprovcod,
			    ordcomcod);
			--se actualiza la auditoria
			update presupuesto_orden
			    set preor_audit = json_build_object(
			        'usu_cod', usucod,
					'usu_login', usulogin,
			        'fecha y hora', to_char(current_timestamp,'dd-mm-yyyy hh24:mi:ss'),
			        'transaccion', upper(transaccion),
			        'presprov_cod', presprovcod,
			        'ordcom_cod', ordcomcod
			    )
		    WHERE preor_cod = preorcod;
		  --SE MODIFICA EL ESTADO DE pedido_compra_cab
		   	UPDATE pedido_compra_cab 
				SET pedcom_estado = 'APROBADO',
				usu_cod = usucod
	        WHERE pedcom_cod = pedcomcod;
	        --SE MODIFICA EL ESTADO DE presupuesto_prov_cab
	        UPDATE presupuesto_prov_cab 
				SET presprov_estado = 'APROBADO',
				usu_cod = usucod
	        WHERE presprov_cod = presprovcod;
	    	raise notice 'LA ORDEN FUE REGISTADA CON EXITO';
    end if;
    if operacion = 2 then
        -- aqui hacemos un update
		update orden_compra_cab 
			SET ordcom_estado = 'ANULADO',
			usu_cod = usucod
        WHERE ordcom_cod = ordcomcod;
       --SE MODIFICA EL ESTADO DE presupuesto_prov_cab
	    UPDATE presupuesto_prov_cab 
			SET presprov_estado = 'ACTIVO',
			usu_cod = usucod
        WHERE presprov_cod = presprovcod;
		--SE MODIFICA EL ESTADO DE pedido_compra_cab
	   	UPDATE pedido_compra_cab 
			SET pedcom_estado = 'ACTIVO',
			usu_cod = usucod	
		WHERE pedcom_cod = pedcomcod;
        raise notice 'LA ORDEN FUE ANULADA CON EXITO';
    end if;
	--se selecciona la ultima auditoria
	select coalesce(ordcom_audit,'') into ordcomaudit
	from orden_compra_cab
	where ordcom_cod = ordcomcod;

	--se actualiza la auditoria
	update orden_compra_cab
    set ordcom_audit = ordcomaudit||' '||json_build_object(
        'usu_cod', usucod,
		'usu_login', usulogin,
        'fecha y hora', to_char(current_timestamp,'dd-mm-yyyy hh24:mi:ss'),
        'transaccion', upper(transaccion),
        'ordcom_fecha', to_char(ordcomfecha,'dd-mm-yyyy'),
        'ordcom_condicionpago', ordcomcondicionpago,
        'ordcom_cuota', ordcomcuota,
        'ordcom_intefecha', upper(ordcomintefecha),
        'ordcom_montocuota', ordcommontocuota,
        'pro_cod', procod,
        'tiprov_cod', tiprovcod,
        'pro_razonsocial', upper(prorazonsocial),
        'emp_cod', empcod,
        'emp_razonsocial', upper(emprazonsocial),
        'suc_cod', succod,
		'suc_descri', upper(sucdescri), 
		'ordcom_estado', upper(ordcomestado)
    )||','
    WHERE ordcom_cod = ordcomcod;

	--se abre el cursor de presupuesto proveedor
	for presup in cu_presupuesto loop
		--se actualiza la auditoria
		update presupuesto_prov_cab
	    set presprov_audit = presup.presprovaudit||' '||json_build_object(
	        'usu_cod', usucod,
			'usu_login', usulogin,
	        'fecha y hora', to_char(current_timestamp,'dd-mm-yyyy hh24:mi:ss'),
	        'transaccion', 'MODIFICACION (ORDEN COMPRA N° '||ordcomcod||')',
	        'presprov_fecha', presup.presprovfecha,
	        'presprov_fechavenci', presup.presprovfechavenci,
	        'tiprov_cod', tiprovcod,
	        'pro_cod', procod,
	        'pro_ruc', presup.proruc,
	        'pro_razonsocial', upper(prorazonsocial),
	        'emp_cod', empcod,
	        'emp_razonsocial', upper(emprazonsocial),
	        'suc_cod', succod,
			'suc_descri', upper(sucdescri), 
			'presprov_estado', presup.presprovestado
	    )||','
	    WHERE presprov_cod = presprovcod;
	end loop;

	--se abre el cursor de pedido de compras
	for ped in cu_pedido loop
		--se actualiza la auditoria
		update pedido_compra_cab
	    set pedcom_audit = ped.pedcomaudit||' '||json_build_object(
	        'usu_cod', usucod,
			'usu_login', usulogin,
	        'fecha y hora', to_char(current_timestamp,'dd-mm-yyyy hh24:mi:ss'),
	        'transaccion', 'MODIFICACION (ORDEN COMPRA N° '||ordcomcod||')',
	        'emp_cod', empcod,
	        'emp_razonsocial', upper(emprazonsocial),
	        'suc_cod', succod,
			'suc_descri', upper(sucdescri), 
			'pedcom_estado', ped.pedcomestado 
	    )||','
	    WHERE pedcom_cod = pedcomcod;
	end loop;
end
$$
language plpgsql;

--sp_orden_compra_det (ORDEN COMPRA DETALLE)
CREATE OR REPLACE FUNCTION sp_orden_compra_det
(itmcod integer, 
tipitemcod integer, 
ordcomcod integer, 
ordcomdetcantidad numeric, 
ordcomdetprecio numeric, 
operacion integer)
RETURNS void
AS $$
begin 
	if operacion = 1 then
	        -- aqui hacemos un insert
	        INSERT INTO orden_compra_det 
	        (itm_cod,
	        tipitem_cod, 
	        ordcom_cod, 
	        ordcomdet_cantidad,
	        ordcomdet_precio)
	        VALUES(
	        itmcod,
	        tipitemcod,
	        ordcomcod,
	        ordcomdetcantidad,
	        ordcomdetprecio);
	        raise notice 'EL DETALLE FUE REGISTADO CON EXITO';
	end if;
    if operacion = 2 then
    	-- aqui hacemos un delete
		delete from orden_compra_det 
		where 
		itm_cod = itmcod 
		and tipitem_cod = tipitemcod 
		and ordcom_cod = ordcomcod;
		raise notice 'EL DETALLE FUE ELIMINADO CON ÉXITO';
	end if;
end
$$
language plpgsql;

--sp_compra_cab (COMPRA CABECERA)
CREATE OR REPLACE FUNCTION sp_compra_cab
(comcod integer,
comfecha date,
comnrofac varchar,
comtipfac tipofac,
comcuotas integer,
comintefecha varchar,
comestado varchar,
procod integer,
tiprovcod integer,
usucod integer,
succod integer,
empcod integer,
ordcomcod integer,
commontocuota numeric,
comtimbrado varchar,
tipcompcod integer,
operacion integer,
prorazonsocial varchar,
usulogin varchar,
sucdescri varchar,
emprazonsocial varchar,
transaccion varchar)
RETURNS void
AS $$
declare 
	comdet record;
	comaudit text;
	comorcod integer := (select coalesce (max(comor_cod),0)+1 from compra_orden);
	libcomcod integer:= (select coalesce (max(libcom_cod),0)+1 from libro_compras);
	cu_orden cursor is
		select 
			coalesce(occ.ordcom_audit,'') as ordcomaudit,
			to_char(occ.ordcom_fecha,'dd-mm-yyyy') as ordcomfecha,
			occ.ordcom_condicionpago as ordcomcondicionpago,
			occ.ordcom_estado as ordcomestado
		from orden_compra_cab occ
		where occ.ordcom_cod = ordcomcod;
	cu_libcom cursor is
		select lc.* from libro_compras lc
		where lc.com_cod = comcod;
	cu_cuenpag cursor is
		select cp.* from cuentas_pagar cp
		where cp.com_cod = comcod;
begin 
    if operacion = 1 then
		perform * from compra_cab
		where (com_nrofac = comnrofac and com_estado = 'ACTIVO');
		if found then
			raise exception '1';
	    elseif operacion = 1 then
        -- aqui hacemos un insert
	        INSERT INTO compra_cab 
		        (com_cod,
		        com_fecha,
		        com_nrofac,
		        com_tipfac,
		        com_cuotas,
		        com_intefecha,
		        com_estado,
		        pro_cod,
		        tiprov_cod,
		        usu_cod,
		        suc_cod,
		        emp_cod,
				com_montocuota,
				com_timbrado,
				tipcomp_cod)
	        VALUES(
		        comcod,
		        comfecha,
		        comnrofac,
		        comtipfac,
		        comcuotas,
		        upper(comintefecha),
		      	'ACTIVO',
		      	procod,
		      	tiprovcod,
		    	usucod,
		        succod,
		      	empcod,
				commontocuota,
				comtimbrado,
				tipcompcod);
	    --INSERTA DATOS EN compra_orden
		    INSERT INTO compra_orden
			    (comor_cod,
			    ordcom_cod,
			    com_cod)
		    values(
			    comorcod,
			    ordcomcod,
			    comcod);
			--se actualiza la auditoria de compra_orden
			update compra_orden
			    set comor_audit = json_build_object(
			        'usu_cod', usucod,
					'usu_login', usulogin,
			        'fecha y hora', to_char(current_timestamp,'dd-mm-yyyy hh24:mi:ss'),
			        'transaccion', upper(transaccion),
			        'ordcom_cod', ordcomcod,
					'com_cod', comcod
			    )
    		WHERE com_cod = comcod;
		  --SE MODIFICA EL ESTADO DE ORDEN_compra_cab
		   	UPDATE orden_compra_cab 
				SET ordcom_estado = 'RECIBIDO',
				usu_cod = usucod
	        WHERE ordcom_cod = ordcomcod;
	      --SE indertan datos en libro_compras
	        insert into libro_compras
		        (com_cod,
		        libcom_cod,
		        libcom_fecha,
		        libcom_nro_comprobante,
		        libcom_exenta,
		        libcom_iva5,
		        libcom_iva10,
		        libcom_estado,
				tipcomp_cod)
	        values
		        (comcod,
		        libcomcod,
		        comfecha,
		        comnrofac,
		        0,
		        0,
		        0,
		        'ACTIVO',
				tipcompcod);
	      --INSERTA DATOS EN cuentas_pagar
	        insert into cuentas_pagar 
		        (com_cod,
		        cuenpag_cuotas,
		        cuenpag_monto,
		        cuenpag_saldo,
		        cuenpag_estado)
		    values
		        (comcod,
		        comcuotas,
		        0,
		        0,
		        'ACTIVO');
	    	raise notice 'LA COMPRA FUE REGISTADA CON EXITO';
	    end if;
    end if;
    if operacion = 2 then
        -- aqui hacemos un update
		update compra_cab 
			SET com_estado = 'ANULADO',
			usu_cod = usucod
        WHERE com_cod = comcod;
       --SE MODIFICA EL ESTADO DE ORDEN_compra_cab
		UPDATE orden_compra_cab 
			SET ordcom_estado = 'ACTIVO',
			usu_cod = usucod
        WHERE ordcom_cod = ordcomcod;
       --ANULAMOS LIBRO COMPRAS
        update libro_compras 
        	SET libcom_estado = 'ANULADO'
        WHERE com_cod = comcod;
        --ANULAMOS CUENTAS PAGAR
        update cuentas_pagar 
        	SET cuenpag_estado = 'ANULADO'
        WHERE com_cod = comcod;
        -- ACTUALIZA STOCK (RESTA)
		for comdet in select * from compra_det where com_cod = comcod loop
			UPDATE stock
				set sto_cantidad = sto_cantidad - comdet.comdet_cantidad
			where itm_cod = comdet.itm_cod
				and tipitem_cod = comdet.tipitem_cod
				and dep_cod = comdet.dep_cod
				and suc_cod = comdet.suc_cod
				and emp_cod = comdet.emp_cod;
		end loop;
		raise notice 'LA COMPRA FUE ANULADA CON EXITO';
    end if;
	--se selecciona la ultima auditoria
	select coalesce(com_audit,'') into comaudit
	from compra_cab
	where com_cod = comcod;

	--se actualiza la auditoria
	update compra_cab
	    set com_audit = comaudit||' '||json_build_object(
	        'usu_cod', usucod,
			'usu_login', usulogin,
	        'fecha y hora', to_char(current_timestamp,'dd-mm-yyyy hh24:mi:ss'),
	        'transaccion', upper(transaccion),
	        'com_fecha', to_char(comfecha,'dd-mm-yyyy'),
	        'tipcomp_cod', tipcompcod,
	        'com_nrofac', comnrofac,
	        'com_tipfac', comtipfac,
	        'com_cuotas', comcuotas,
	        'com_intefecha', upper(comintefecha),
	        'com_montocuota', commontocuota,
	        'pro_cod', procod,
	        'tiprov_cod', tiprovcod,
	        'pro_razonsocial', upper(prorazonsocial),
			'com_timbrado', comtimbrado,
	        'emp_cod', empcod,
	        'emp_razonsocial', upper(emprazonsocial),
	        'suc_cod', succod,
			'suc_descri', upper(sucdescri), 
			'com_estado', upper(comestado)
	    )||','
    WHERE com_cod = comcod;

	--se abre el cursor de libro_compras
	for libro in cu_libcom loop
	--se actualiza la auditoria de libro_compras
		update libro_compras
			set libcom_audit = coalesce(libro.libcom_audit,'')||' '||json_build_object(
			    'usu_cod', usucod,
				'usu_login', usulogin,
			    'fecha y hora', to_char(current_timestamp,'dd-mm-yyyy hh24:mi:ss'),
			    'transaccion', upper(transaccion),
				'com_cod', libro.com_cod,
			    'libcom_fecha', to_char(libro.libcom_fecha,'dd-mm-yyyy'),
				'tipcomp_cod', libro.tipcomp_cod,
				'libcom_nro_comprobante', libro.libcom_nro_comprobante,
				'libcom_exenta', libro.libcom_exenta,
				'libcom_iva5', libro.libcom_iva5,
				'libcom_iva10', libro.libcom_iva10,
				'libcom_estado', libro.libcom_estado
			)||','
	    WHERE com_cod = comcod;
	end loop;

	--se abre el cursor de orden_compra
	for orden in cu_orden loop
		--se actualiza la auditoria de orden_compra
		update orden_compra_cab
		    set ordcom_audit = orden.ordcomaudit||' '||json_build_object(
		        'usu_cod', usucod,
				'usu_login', usulogin,
		        'fecha y hora', to_char(current_timestamp,'dd-mm-yyyy hh24:mi:ss'),
		        'transaccion', 'MODIFICACION (COMPRA N° '||comcod||')',
		        'ordcom_fecha', orden.ordcomfecha,
		        'ordcom_condicionpago', orden.ordcomcondicionpago,
	        	'ordcom_cuota', comcuotas,
	        	'ordcom_intefecha', upper(comintefecha),
	        	'ordcom_montocuota', commontocuota,
		        'pro_cod', procod,
		        'tiprov_cod', tiprovcod,
		        'pro_razonsocial', upper(prorazonsocial),
		        'emp_cod', empcod,
		        'emp_razonsocial', upper(emprazonsocial),
		        'suc_cod', succod,
				'suc_descri', upper(sucdescri), 
				'ordcom_estado', orden.ordcomestado
		    )||','
	    WHERE ordcom_cod = ordcomcod;
	end loop;		
end
$$
language plpgsql;

--sp_compra_det (ORDEN COMPRA DETALLE)
CREATE OR REPLACE FUNCTION sp_compra_det
(itmcod integer, 
tipitemcod integer, 
depcod integer,
succod integer,
empcod integer,
comcod integer, 
comdetcantidad numeric, 
comdetprecio integer,
operacion integer)
RETURNS void
AS $$
declare cantitem integer;
begin 
	if operacion = 1 then
		perform * from compra_det
		where itm_cod = itmcod and com_cod = comcod;
		if found then
			raise exception '1';
	    elseif operacion = 1 then
			perform * from stock
			where itm_cod = itmcod and tipitem_cod = tipitemcod and dep_cod = depcod and suc_cod = succod and emp_cod = empcod;
			if found then
		       	-- ACTUALIZAR O INSERTAR DATOS DE STOCK (SUMA)
				UPDATE stock 
				set sto_cantidad = sto_cantidad + comdetcantidad
				where itm_cod = itmcod
					and tipitem_cod = tipitemcod
					and dep_cod = depcod
					and suc_cod = succod
					and emp_cod = empcod;
			else
				if tipitemcod = 1 then
					cantitem = 0;
				else 
					cantitem = comdetcantidad;
				end if;
				INSERT INTO stock
					(itm_cod, 
					tipitem_cod, 
					dep_cod, 
					suc_cod, 
					emp_cod, 
					sto_cantidad)
				VALUES(	
					itmcod, 
					tipitemcod, 
					depcod, 
					succod, 
					empcod, 
					cantitem);
			end if;
	        -- aqui hacemos un insert
	        INSERT INTO compra_det 
		        (itm_cod,
		        tipitem_cod, 
		        dep_cod, 
		        suc_cod,
		        emp_cod,
		        com_cod,
		        comdet_cantidad,
		        comdet_precio)
	        VALUES(
		        itmcod,
		        tipitemcod,
		        depcod,
		        succod,
		        empcod,
		        comcod,
		        comdetcantidad,
		        comdetprecio);
			raise notice 'EL DETALLE FUE REGISTADO CON EXITO';
	    end if;
	end if;
    if operacion = 2 then
    	-- aqui hacemos un delete
		delete from compra_det
		where itm_cod = itmcod
				and tipitem_cod = tipitemcod
				and dep_cod = depcod
				and suc_cod = succod
				and emp_cod = empcod
				and com_cod = comcod;
		-- ACTUALIZA STOCK (RESTA)
			UPDATE stock
			set sto_cantidad = sto_cantidad - comdetcantidad
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

--sp_libro_compras (LIBRO COMPRAS)
create or replace function sp_libro_compras 
(comcod integer,
libcomnrocomprobante varchar,
exenta numeric,
iva5 numeric,
iva10 numeric,
tipcompcod integer,
operacion integer,
usucod integer,
usulogin varchar)
returns void
as $$
declare
	cu_libcom cursor is
		select lc.* from libro_compras lc
		where lc.com_cod = comcod and lc.libcom_nro_comprobante = libcomnrocomprobante and lc.tipcomp_cod = tipcompcod;
begin
	if operacion = 1 then
		update libro_compras
		set libcom_exenta = libcom_exenta + exenta,
		libcom_iva5 = libcom_iva5 + iva5,
		libcom_iva10 = libcom_iva10 + iva10
		where com_cod = comcod and libcom_nro_comprobante = libcomnrocomprobante and tipcomp_cod = tipcompcod;
	end if;
	if operacion = 2 then
		update libro_compras
		set libcom_exenta = libcom_exenta - exenta,
		libcom_iva5 = libcom_iva5 - iva5,
		libcom_iva10 = libcom_iva10 - iva10
		where com_cod = comcod and libcom_nro_comprobante = libcomnrocomprobante and tipcomp_cod = tipcompcod;
	end if;

	--se abre el cursor de libro_compras
	for libro in cu_libcom loop
	--se actualiza la auditoria de libro_compras
		update libro_compras
		set libcom_audit = coalesce(libro.libcom_audit,'')||' '||json_build_object(
		    'usu_cod', usucod,
			'usu_login', usulogin,
		    'fecha y hora', to_char(current_timestamp,'dd-mm-yyyy hh24:mi:ss'),
		    'transaccion', 'MODIFICACION',
			'com_cod', libro.com_cod,
		    'libcom_fecha', to_char(libro.libcom_fecha,'dd-mm-yyyy'),
			'libcom_nro_comprobante', libro.libcom_nro_comprobante,
			'libcom_exenta', libro.libcom_exenta,
			'libcom_iva5', libro.libcom_iva5,
			'libcom_iva10', libro.libcom_iva10,
			'libcom_estado', libro.libcom_estado
		)||','
		where com_cod = comcod and libcom_nro_comprobante = libcomnrocomprobante and tipcomp_cod = tipcompcod;
	end loop;
end
$$
language plpgsql;


--sp_cuentas_pagar (CUENTAS A PAGAR)
create or replace function sp_cuentas_pagar 
(comcod integer,
monto integer,
saldo integer,
operacion integer,
usucod integer,
usulogin varchar)
returns void
as $$
declare
	cu_cuenpag cursor is
		select cp.* from cuentas_pagar cp
		where cp.com_cod = comcod;
begin
	if operacion = 1 then
		update cuentas_pagar
		set cuenpag_monto = cuenpag_monto + monto,
		cuenpag_saldo = cuenpag_saldo + saldo
		where com_cod = comcod;
	end if;
	if operacion = 2 then
		update cuentas_pagar
		set cuenpag_monto = cuenpag_monto - monto,
		cuenpag_saldo = cuenpag_saldo - saldo
		where com_cod = comcod;
	end if;

--se abre el cursor de cuentas_pagar
	for cuenta in cu_cuenpag loop
	--se actualiza la auditoria de cuentas_pagar
		update cuentas_pagar
		set cuenpag_audit = coalesce(cuenta.cuenpag_audit,'')||' '||json_build_object(
		    'usu_cod', usucod,
			'usu_login', usulogin,
		    'fecha y hora', to_char(current_timestamp,'dd-mm-yyyy hh24:mi:ss'),
		    'transaccion', 'MODIFICACION',
			'cuenpag_cuotas', cuenta.cuenpag_cuotas,
			'cuenpag_monto', cuenta.cuenpag_monto,
			'cuenpag_saldo', cuenta.cuenpag_saldo,
			'cuenpag_estado', cuenta.cuenpag_estado
		)||','
	    WHERE com_cod = comcod;
	end loop;
end
$$
language plpgsql;

--sp_ajuste_inventario_cab (AJUSTE INVENTARIO CABECERA)
CREATE OR REPLACE FUNCTION sp_ajuste_inventario_cab
(ajinvcod integer,
ajinvfecha date,
ajinvtipoajuste tipajus,
ajinvestado varchar,
succod integer,
empcod integer,
usucod integer,
operacion_cab integer,
sucdescri varchar,
emprazonsocial varchar,
usulogin varchar,
transaccion varchar)
RETURNS void
AS $$
declare 
	ajuste_det record;
	ajinvaudit text;
begin 
    if operacion_cab = 1 then
        -- aqui hacemos un insert
        INSERT INTO ajuste_inventario_cab 
	        (ajinv_cod,
	        ajinv_fecha,
	        ajinv_tipoajuste,
	        ajinv_estado,
	        suc_cod,
	        emp_cod,
	        usu_cod)
        VALUES(
	        ajinvcod,
	        ajinvfecha,
	        ajinvtipoajuste,
	      	'ACTIVO',
	        succod,
	      	empcod,
	        usucod);
        raise notice 'EL AJUSTE FUE REGISTADO CON EXITO';
    end if;
    if operacion_cab = 2 then
        -- aqui hacemos un update
		update ajuste_inventario_cab 
			SET ajinv_estado = 'ANULADO',
			usu_cod = usucod
        WHERE ajinv_cod = ajinvcod;
       	-- SE ACTUALIZA EL STOCK
       	if ajinvtipoajuste = 'POSITIVO' then
	        for ajuste_det in select * from ajuste_inventario_det where ajinv_cod = ajinvcod loop
				UPDATE stock
					set sto_cantidad = sto_cantidad - ajuste_det.ajinvdet_cantidad
				where itm_cod = ajuste_det.itm_cod
					and tipitem_cod = ajuste_det.tipitem_cod
					and dep_cod = ajuste_det.dep_cod
					and suc_cod = ajuste_det.suc_cod
					and emp_cod = ajuste_det.emp_cod;
			end loop;
		elseif ajinvtipoajuste = 'NEGATIVO' then
			for ajuste_det in select * from ajuste_inventario_det where ajinv_cod = ajinvcod loop
				UPDATE stock s
					set sto_cantidad = sto_cantidad + ajuste_det.ajinvdet_cantidad
				where itm_cod = ajuste_det.itm_cod
					and tipitem_cod = ajuste_det.tipitem_cod
					and dep_cod = ajuste_det.dep_cod
					and suc_cod = ajuste_det.suc_cod
					and emp_cod = ajuste_det.emp_cod;
			end loop;
		end if;
        raise notice 'EL AJUSTE FUE ANULADO';
    end if;
	--se selecciona la ultima auditoria
	select coalesce(ajinv_audit,'') into ajinvaudit
	from ajuste_inventario_cab
	where ajinv_cod = ajinvcod;

	--se actualiza la auditoria
	update ajuste_inventario_cab
    set ajinv_audit = ajinvaudit||' '||json_build_object(
        'usu_cod', usucod,
		'usu_login', usulogin,
        'fecha y hora', to_char(current_timestamp,'dd-mm-yyyy hh24:mi:ss'),
        'transaccion', upper(transaccion),
        'ajinv_fecha', to_char(ajinvfecha,'dd-mm-yyyy'),
        'ajinv_tipoajuste', ajinvtipoajuste,
        'emp_cod', empcod,
        'emp_razonsocial', upper(emprazonsocial),
        'suc_cod', succod,
		'suc_descri', upper(sucdescri), 
		'ajinv_estado', upper(ajinvestado)
    )||','
    WHERE ajinv_cod = ajinvcod;
end
$$
language plpgsql;

--sp_ajuste_inventario_det (AJUSTE INVENTARIO DETALLE)
CREATE OR REPLACE FUNCTION sp_ajuste_inventario_det
(itmcod integer, 
tipitemcod integer, 
depcod integer,
succod integer,
empcod integer,
ajinvcod integer, 
ajinvdetmotivo varchar, 
ajinvdetcantidad numeric, 
ajinvdetprecio numeric,
ajinvtipoajuste varchar,
operacion integer)
RETURNS void
AS $$
begin 
	if operacion = 1 then
		perform * from ajuste_invenario_det
		where itm_cod = itmcod and dep_cod = depcod and ajinv_cod = ajinvcod;
		if found then
			raise exception '1';
	    elseif operacion = 1 then
	        -- aqui hacemos un insert
	        INSERT INTO ajuste_invenario_det 
	        (itm_cod,
	        tipitem_cod, 
	        dep_cod, 
	        suc_cod,
	        emp_cod,
	        ajinv_cod,
	        ajinvdet_motivo,
	        ajinvdet_cantidad,
			ajinvdet_precio)
	        VALUES(
	        itmcod,
	        tipitemcod,
	        depcod,
	        succod,
	        empcod,
	        ajinvcod,
	        upper(ajinvdetmotivo),
	        ajinvdetcantidad,
			ajinvdetprecio);
	       -- ACTUALIZA STOCK (SUMA)
	        if ajinvtipoajuste = 'POSITIVO' then
				UPDATE stock 
				set sto_cantidad = sto_cantidad + ajinvdetcantidad
				where itm_cod = itmcod
					and tipitem_cod = tipitemcod
					and dep_cod = depcod
					and suc_cod = succod
					and emp_cod = empcod;
			-- ACTUALIZA STOCK (RESTA)
			elseif ajinvtipoajuste = 'NEGATIVO' then
				UPDATE stock 
				set sto_cantidad = sto_cantidad - ajinvdetcantidad
				where itm_cod = itmcod
					and tipitem_cod = tipitemcod
					and dep_cod = depcod
					and suc_cod = succod
					and emp_cod = empcod;
			end if;
		raise notice 'EL DETALLE FUE REGISTADO CON EXITO';
	    end if;
	end if;
    if operacion = 2 then
    	-- aqui hacemos un delete
		delete from ajuste_invenario_det
		where itm_cod = itmcod
				and tipitem_cod = tipitemcod
				and dep_cod = depcod
				and suc_cod = succod
				and emp_cod = empcod
				and ajinv_cod = ajinvcod;
		-- ACTUALIZA STOCK (RESTA)
		if ajinvtipoajuste = 'POSITIVO' then
				UPDATE stock
				set sto_cantidad = sto_cantidad - ajinvdetcantidad
				where itm_cod = itmcod
					and tipitem_cod = tipitemcod
					and dep_cod = depcod
					and suc_cod = succod
					and emp_cod = empcod;
		-- ACTUALIZA STOCK (SUMA)
		elseif ajinvtipoajuste = 'NEGATIVO' then
			UPDATE stock 
			set sto_cantidad = sto_cantidad + ajinvdetcantidad
			where itm_cod = itmcod
				and tipitem_cod = tipitemcod
				and dep_cod = depcod
				and suc_cod = succod
				and emp_cod = empcod;
		end if;
		raise notice 'EL DETALLE FUE ELIMINADO CON EXITO';
	end if;
end
$$
language plpgsql;

--sp_nota_compra_cab (NOTA COMPRA CABECERA)
CREATE OR REPLACE FUNCTION sp_nota_compra_cab
(notacomcod integer,
notacomfecha date,
notacomnronota varchar,
notacomconcepto varchar,
notacomestado varchar,
procod integer,
tiprovcod integer,
succod integer,
empcod integer,
usucod integer,
comcod integer,
tipcompcod integer,
operacion integer,
prorazonsocial varchar,
usulogin varchar,
sucdescri varchar,
emprazonsocial varchar,
transaccion varchar)
RETURNS void
AS $$
declare 
	notacomaudit text;
	libcomcod integer:= (select coalesce (max(libcom_cod),0)+1 from libro_compras);
	cu_ncdet cursor is
		select ncd.itm_cod,
			ncd.tipitem_cod,
			ncd.notacomdet_cantidad,
			ncd.notacomdet_precio,
			ncd.dep_cod,
			ncd.suc_cod,
			ncd.emp_cod 
		from nota_compra_det ncd 
		where ncd.notacom_cod = notacomcod;
	cu_libcom cursor is
		select * from libro_compras 
		where com_cod = comcod and libcom_nro_comprobante = notacomnronota and tipcomp_cod = tipcompcod;
	cu_cuenpag cursor is
		select cp.* from cuentas_pagar cp
		where cp.com_cod = comcod;
	total integer := (select sum(notacomdet_cantidad * notacomdet_precio) from nota_compra_det where notacom_cod = notacomcod);
	cu_compra_cab cursor is
		select cc.* ,
			e.emp_razonsocial,
			s.suc_descri,
			p.pro_razonsocial||' - '||tp.tiprov_descripcion as pro_razonsocial
		from compra_cab cc
			join sucursales s on s.suc_cod = cc.suc_cod and s.emp_cod = cc.emp_cod
			    join empresa e on e.emp_cod = s.emp_cod
			join proveedor p on p.pro_cod = cc.pro_cod and p.tiprov_cod = cc.tiprov_cod
				join tipo_proveedor tp on tp.tiprov_cod = p.tiprov_cod
		where cc.com_cod = comcod;
	lib record;
begin 
    if operacion = 1 then
		perform * from nota_compra_cab
		where notacom_nronota = notacomnronota 
			and pro_cod = procod 
			and tipcomp_cod = tipcompcod 
			and notacom_estado = 'ACTIVO';
		if found then
			raise exception 'rep';
	    elseif operacion = 1 then
        -- aqui hacemos un insert
	        INSERT INTO nota_compra_cab 
		        (notacom_cod,
		        notacom_fecha,
		        notacom_nronota, 
		        notacom_concepto,
		        notacom_estado,
		        pro_cod,
		        tiprov_cod,
		        suc_cod,
		        emp_cod,
		        usu_cod,
		        com_cod,
		        tipcomp_cod)
	        VALUES(
		        notacomcod,
		        notacomfecha,
		        notacomnronota,
		        upper(notacomconcepto),
		      	'ACTIVO',
		      	procod,
		      	tiprovcod,
		        succod,
		      	empcod,
		    	usucod,
		    	comcod,
		    	tipcompcod);
				if tipcompcod in (1,2) then
					insert into libro_compras
				        (com_cod,
				        libcom_cod,
				        libcom_fecha,
				        libcom_nro_comprobante,
				        libcom_exenta,
				        libcom_iva5,
				        libcom_iva10,
				        libcom_estado,
						tipcomp_cod)
				    values
				        (comcod,
				        libcomcod,
				        notacomfecha,
				        notacomnronota,
				        0,
				        0,
				        0,
				        'ACTIVO',
						tipcompcod);
				end if;
	    	raise notice 'LA NOTA FUE REGISTADA CON EXITO';
	  	end if;
    end if;
    if operacion = 2 then
        -- aqui hacemos un update
		update nota_compra_cab 
			SET notacom_estado = 'ANULADO',
			usu_cod = usucod
        WHERE notacom_cod = notacomcod;
	    --ANULAMOS LIBRO COMPRAS DE LA NOTA
		if tipcompcod in (1,2) then
	        update libro_compras 
	        	SET libcom_estado = 'ANULADO'
			where com_cod = comcod and tipcomp_cod = tipcompcod and libcom_nro_comprobante = notacomnronota;
		end if;
	    --_____________________________ACTUALIZA TABLAS INOLUCRADAS_____________________________
		if tipcompcod = 1 then -->(nota de credito)
			for ncdet in cu_ncdet loop
				UPDATE stock
					set sto_cantidad = sto_cantidad + ncdet.notacomdet_cantidad
				where itm_cod = ncdet.itm_cod
					and tipitem_cod = ncdet.tipitem_cod
					and dep_cod = ncdet.dep_cod
					and suc_cod = ncdet.suc_cod
					and emp_cod = ncdet.emp_cod;
			end loop;
			--Si la cantidad * precio de nota compra detalle es mayor a 0				
			if total > 0 then
				/**************SE ACTUALIZA CUENTAS A PAGAR**************/
				--se actualiza estado a 'ACTIVO'
				update cuentas_pagar
					set cuenpag_estado = 'ACTIVO'
				where com_cod = comcod;
				--se llama al sp de cuentas a pagar para restar los montos y auditar
				perform sp_cuentas_pagar (
					comcod, 
					total,
					total,
					1,
					usucod,
					usulogin);
				/**************SE ACTUALIZA COMPRAS CABECERA**************/
				--actualiza estado a 'ACTIVO'
				update compra_cab
					set com_estado = 'ACTIVO',
					usu_cod = usucod
				where com_cod = comcod;
				for com in cu_compra_cab loop
					--actualiza auditoria
					update compra_cab
					    set com_audit = coalesce(com.com_audit)||' '||json_build_object(
					        'usu_cod', usucod,
							'usu_login', usulogin,
					        'fecha y hora', to_char(current_timestamp,'dd-mm-yyyy hh24:mi:ss'),
					        'transaccion', 'MODIFICACION (NOTA COMPRA N° '||notacomcod||')',
					        'com_fecha', to_char(com.com_fecha,'dd-mm-yyyy'),
					        'tipcomp_cod', com.tipcomp_cod,
					        'com_nrofac', com.com_nrofac,
					        'com_tipfac', com.com_tipfac,
					        'com_cuotas', com.com_cuotas,
					        'com_intefecha', upper(com.com_intefecha),
					        'com_montocuota', com.com_montocuota,
					        'pro_cod', com.pro_cod,
					        'tiprov_cod', com.tiprov_cod,
					        'pro_razonsocial', upper(com.pro_razonsocial),
							'com_timbrado', com.com_timbrado,
					        'emp_cod', com.emp_cod,
					        'emp_razonsocial', upper(com.emp_razonsocial),
					        'suc_cod', com.suc_cod,
							'suc_descri', upper(com.suc_descri), 
							'com_estado', upper(com.com_estado)
					    )||','
				    WHERE com_cod = comcod;
				end loop;
			end if;	    
		elseif tipcompcod = 2 then -->(nota de debito)
			for ncdet in cu_ncdet loop
				UPDATE stock
					set sto_cantidad = sto_cantidad - ncdet.notacomdet_cantidad
				where itm_cod = ncdet.itm_cod
					and tipitem_cod = ncdet.tipitem_cod
					and dep_cod = ncdet.dep_cod
					and suc_cod = ncdet.suc_cod
					and emp_cod = ncdet.emp_cod;
			end loop;
			--Si la cantidad * precio de nota compra detalle es mayor a 0	
			if total > 0 then
				/**************SE ACTUALIZA CUENTAS A PAGAR**************/
				--se llama al sp de cuentas a pagar para restar los montos y auditar
				perform sp_cuentas_pagar (
					comcod, 
					total,
					total,
					2,
					usucod,
					usulogin);
			end if;	    
		end if;
        raise notice 'LA NOTA FUE ANULADA';
    end if;
	--se selecciona la ultima auditoria
	select coalesce(notacom_audit,'') into notacomaudit
	from nota_compra_cab
	where notacom_cod = notacomcod;

	--se actualiza la auditoria
	update nota_compra_cab
	    set notacom_audit = notacomaudit||' '||json_build_object(
	        'usu_cod', usucod,
			'usu_login', usulogin,
	        'fecha y hora', to_char(current_timestamp,'dd-mm-yyyy hh24:mi:ss'),
	        'transaccion', upper(transaccion),
	        'notacom_fecha', to_char(notacomfecha,'dd-mm-yyyy'),
	        'notacom_concepto', upper(notacomconcepto),
	        'pro_cod', procod,
	        'tiprov_cod', tiprovcod,
	        'pro_razonsocial', upper(prorazonsocial),
	        'com_cod', comcod,
	        'emp_cod', empcod,
	        'emp_razonsocial', upper(emprazonsocial),
	        'suc_cod', succod,
			'suc_descri', upper(sucdescri), 
			'notacom_estado', upper(notacomestado)
	    )||','
    WHERE notacom_cod = notacomcod;

	--se abre el cursor de libro_compras
	for libro in cu_libcom loop
	--se actualiza la auditoria de libro_compras correspondiente a la nota
		update libro_compras
			set libcom_audit = coalesce(libro.libcom_audit,'')||' '||json_build_object(
			    'usu_cod', usucod,
				'usu_login', usulogin,
			    'fecha y hora', to_char(current_timestamp,'dd-mm-yyyy hh24:mi:ss'),
			    'transaccion', upper(transaccion),
				'com_cod', libro.com_cod,
			    'libcom_fecha', to_char(libro.libcom_fecha,'dd-mm-yyyy'),
				'tipcomp_cod', libro.tipcomp_cod,
				'libcom_nro_comprobante', libro.libcom_nro_comprobante,
				'libcom_exenta', libro.libcom_exenta,
				'libcom_iva5', libro.libcom_iva5,
				'libcom_iva10', libro.libcom_iva10,
				'libcom_estado', libro.libcom_estado
			)||','
	    WHERE com_cod = comcod and libcom_nro_comprobante = notacomnronota and tipcomp_cod = tipcompcod;
	end loop;
end
$$
language plpgsql;

--sp_nota_compra_det (NOTA COMPRA DETALLE)
CREATE OR REPLACE FUNCTION sp_nota_compra_det
(itmcod integer, 
tipitemcod integer, 
notacomcod integer, 
notacomdetcantidad numeric, 
notacomdetprecio numeric, 
depcod integer,
succod integer,
empcod integer,
operacion integer,
comcod integer,
usucod integer,
usulogin varchar)
RETURNS void
AS $$
declare
	tipcompcod integer := (select ncc.tipcomp_cod from nota_compra_cab ncc where ncc.notacom_cod = notacomcod); 
	cu_compra_cab cursor is
		select cc.* ,
			e.emp_razonsocial,
			s.suc_descri,
			p.pro_razonsocial||' - '||tp.tiprov_descripcion as pro_razonsocial
		from compra_cab cc
			join sucursales s on s.suc_cod = cc.suc_cod and s.emp_cod = cc.emp_cod
			     join empresa e on e.emp_cod = s.emp_cod
			join proveedor p on p.pro_cod = cc.pro_cod and p.tiprov_cod = cc.tiprov_cod
				join tipo_proveedor tp on tp.tiprov_cod = p.tiprov_cod
		where cc.com_cod = comcod;
	monto_cuenta integer := (select cp.cuenpag_monto from cuentas_pagar cp where cp.com_cod = comcod);
	estado_cuenta varchar := (select cuenpag_estado from cuentas_pagar where com_cod = comcod);
begin 
	if operacion = 1 then
		perform * from nota_compra_det
		where itm_cod = itmcod 
			and notacom_cod = notacomcod;
		if found then
			raise exception 'rep';
	    elseif operacion = 1 then
	        -- aqui hacemos un insert
	        INSERT INTO nota_compra_det 
		        (itm_cod,
		        tipitem_cod, 
		        notacom_cod, 
		        notacomdet_cantidad,
		        notacomdet_precio,
				dep_cod,
				suc_cod,
				emp_cod)
	        VALUES(
		        itmcod,
		        tipitemcod,
		        notacomcod,
		        notacomdetcantidad,
		        notacomdetprecio,
				depcod,
				succod,
				empcod);
			--_____________________________SE ACTUALIZAN TABLAS REALCIONADAS_____________________________
			if tipcompcod = 1 then -->(nota de credito)
				/**********SE RESTA STOCK**********/
				UPDATE stock
					set sto_cantidad = sto_cantidad - notacomdetcantidad
				where itm_cod = itmcod
					and tipitem_cod = tipitemcod
					and dep_cod = depcod
					and suc_cod = succod
					and emp_cod = empcod;
				/**********SI EL TOTAL DE COMPRA DETALLE Y NOTA DETALLE SON IGUALES**********/
				if monto_cuenta = 0 then
					/**************SE ACTUALIZA CUENTAS A PAGAR**************/
					--se actualiza el estado a anulado
					update cuentas_pagar
						set cuenpag_estado = 'ANULADO'
					where com_cod = comcod;
					--se llama al sp de cuentas a pagar para auditar
					perform sp_cuentas_pagar (
						comcod, 
						0,
						0,
						3,
						usucod,
						usulogin);
					/**************SE ACTUALIZA COMPRAS CABECERA**************/
					--actualiza estado a 'ANULADO'
					update compra_cab
						set com_estado = 'ANULADO',
						usu_cod = usucod
					where com_cod = comcod;
					--actualiza auditoria
					for com in cu_compra_cab loop
						update compra_cab
						    set com_audit = coalesce(com.com_audit)||' '||json_build_object(
						        'usu_cod', usucod,
								'usu_login', usulogin,
						        'fecha y hora', to_char(current_timestamp,'dd-mm-yyyy hh24:mi:ss'),
						        'transaccion', 'MODIFICACION (NOTA COMPRA N° '||notacomcod||')',
						        'com_fecha', to_char(com.com_fecha,'dd-mm-yyyy'),
						        'tipcomp_cod', com.tipcomp_cod,
						        'com_nrofac', com.com_nrofac,
						        'com_tipfac', com.com_tipfac,
						        'com_cuotas', com.com_cuotas,
						        'com_intefecha', upper(com.com_intefecha),
						        'com_montocuota', com.com_montocuota,
						        'pro_cod', com.pro_cod,
						        'tiprov_cod', com.tiprov_cod,
						        'pro_razonsocial', upper(com.pro_razonsocial),
								'com_timbrado', com.com_timbrado,
						        'emp_cod', com.emp_cod,
						        'emp_razonsocial', upper(com.emp_razonsocial),
						        'suc_cod', com.suc_cod,
								'suc_descri', upper(com.suc_descri), 
								'com_estado', upper(com.com_estado)
						    )||','
					    WHERE com_cod = comcod;
					end loop;
				end if;
			elseif tipcompcod = 2 then -->(nota de debito)
				/**********SE SUMA STOCK**********/
				UPDATE stock
					set sto_cantidad = sto_cantidad + notacomdetcantidad
					where itm_cod = itmcod
						and tipitem_cod = tipitemcod
						and dep_cod = depcod
						and suc_cod = succod
						and emp_cod = empcod;
				if monto_cuenta > 0 and estado_cuenta = 'ANULADO' then
					/**************SE ACTUALIZA CUENTAS A PAGAR**************/
					--se actualiza el estado a anulado
					update cuentas_pagar
						set cuenpag_estado = 'ACTIVO'
					where com_cod = comcod;
					--se llama al sp de cuentas a pagar para auditar
					perform sp_cuentas_pagar (
						comcod, 
						0,
						0,
						3,
						usucod,
						usulogin);
					/**************SE ACTUALIZA COMPRAS CABECERA**************/
					--actualiza estado a 'ACTIVO'
					update compra_cab
						set com_estado = 'ACTIVO',
						usu_cod = usucod
					where com_cod = comcod;
					--actualiza auditoria
					for com in cu_compra_cab loop
						update compra_cab
						    set com_audit = coalesce(com.com_audit)||' '||json_build_object(
						        'usu_cod', usucod,
								'usu_login', usulogin,
						        'fecha y hora', to_char(current_timestamp,'dd-mm-yyyy hh24:mi:ss'),
						        'transaccion', 'MODIFICACION (NOTA COMPRA N° '||notacomcod||')',
						        'com_fecha', to_char(com.com_fecha,'dd-mm-yyyy'),
						        'tipcomp_cod', com.tipcomp_cod,
						        'com_nrofac', com.com_nrofac,
						        'com_tipfac', com.com_tipfac,
						        'com_cuotas', com.com_cuotas,
						        'com_intefecha', upper(com.com_intefecha),
						        'com_montocuota', com.com_montocuota,
						        'pro_cod', com.pro_cod,
						        'tiprov_cod', com.tiprov_cod,
						        'pro_razonsocial', upper(com.pro_razonsocial),
								'com_timbrado', com.com_timbrado,
						        'emp_cod', com.emp_cod,
						        'emp_razonsocial', upper(com.emp_razonsocial),
						        'suc_cod', com.suc_cod,
								'suc_descri', upper(com.suc_descri), 
								'com_estado', upper(com.com_estado)
						    )||','
					    WHERE com_cod = comcod;
					end loop;
				end if;
			end if;
	        raise notice 'EL DETALLE FUE REGISTADO CON EXITO';
	    end if;
	end if;
    if operacion = 2 then
    	-- aqui hacemos un delete
		delete from nota_compra_det 
		where  itm_cod = itmcod 
			and tipitem_cod = tipitemcod 
			and dep_cod = depcod
			and suc_cod = succod
			and emp_cod = empcod
			and notacom_cod = notacomcod;
		--_____________________________SE ACTUALIZAN TABLAS REALCIONADAS_____________________________}
		/**********SE RESTA STOCK**********/
		if tipcompcod = 1 then -->(nota de credito)
			UPDATE stock
				set sto_cantidad = sto_cantidad + notacomdetcantidad
				where itm_cod = itmcod
					and tipitem_cod = tipitemcod
					and dep_cod = depcod
					and suc_cod = succod
					and emp_cod = empcod;
			if monto_cuenta > 0 and estado_cuenta = 'ANULADO' then
				/**************SE ACTUALIZA CUENTAS A PAGAR**************/
				--se actualiza el estado a anulado
				update cuentas_pagar
					set cuenpag_estado = 'ACTIVO'
				where com_cod = comcod;
				--se llama al sp de cuentas a pagar para auditar
				perform sp_cuentas_pagar (
					comcod, 
					0,
					0,
					3,
					usucod,
					usulogin);
				/**************SE ACTUALIZA COMPRAS CABECERA**************/
				--actualiza estado a 'ACTIVO'
				update compra_cab
					set com_estado = 'ACTIVO',
					usu_cod = usucod
				where com_cod = comcod;
				--actualiza auditoria
				for com in cu_compra_cab loop
					update compra_cab
					    set com_audit = coalesce(com.com_audit)||' '||json_build_object(
					        'usu_cod', usucod,
							'usu_login', usulogin,
					        'fecha y hora', to_char(current_timestamp,'dd-mm-yyyy hh24:mi:ss'),
					        'transaccion', 'MODIFICACION (NOTA COMPRA N° '||notacomcod||')',
					        'com_fecha', to_char(com.com_fecha,'dd-mm-yyyy'),
					        'tipcomp_cod', com.tipcomp_cod,
					        'com_nrofac', com.com_nrofac,
					        'com_tipfac', com.com_tipfac,
					        'com_cuotas', com.com_cuotas,
					        'com_intefecha', upper(com.com_intefecha),
					        'com_montocuota', com.com_montocuota,
					        'pro_cod', com.pro_cod,
					        'tiprov_cod', com.tiprov_cod,
					        'pro_razonsocial', upper(com.pro_razonsocial),
							'com_timbrado', com.com_timbrado,
					        'emp_cod', com.emp_cod,
					        'emp_razonsocial', upper(com.emp_razonsocial),
					        'suc_cod', com.suc_cod,
							'suc_descri', upper(com.suc_descri), 
							'com_estado', upper(com.com_estado)
					    )||','
				    WHERE com_cod = comcod;
				end loop;
			end if;    
		elseif tipcompcod = 2 then -->(nota de debito)
			UPDATE stock
				set sto_cantidad = sto_cantidad - notacomdetcantidad
				where itm_cod = itmcod
					and tipitem_cod = tipitemcod
					and dep_cod = depcod
					and suc_cod = succod
					and emp_cod = empcod;
		end if;
		raise notice 'EL DETALLE FUE ELIMINADO CON EXITO';
	end if;
end
$$
language plpgsql;

-----------------------------------------------------------VISTAS-----------------------------------------------------------

--v_pedido_compra_cab (PEDIDOS DE COMPRAS CABECERA)
create or replace view v_pedido_compra_cab as
select 
pcc.pedcom_cod,
to_char(pcc.pedcom_fecha, 'dd/mm/yyyy') as pedcom_fecha,
pcc.usu_cod,
u.usu_login,
pcc.suc_cod,
s.suc_descri,
pcc.emp_cod,
e.emp_razonsocial,
pcc.pedcom_estado 
from pedido_compra_cab pcc 
join usuarios u on u.usu_cod = pcc.usu_cod
join sucursales s on s.suc_cod = pcc.suc_cod and s.emp_cod = pcc.emp_cod
	join empresa e on e.emp_cod = s.emp_cod
where pcc.pedcom_estado not ilike 'ANULADO'
order by pcc.pedcom_cod;

--v_pedido_compra_det (PEDIDOS DE COMPRAS DETALLE)
create or replace view v_pedido_compra_det as
select
pcd.pedcom_cod,
pcd.itm_cod,
pcd.tipitem_cod,
i.tipimp_cod,
i.itm_descri,
pcd.pedcomdet_cantidad,
um.uni_descri||' ('||um.uni_simbolo||')' as uni_descri,
pcd.pedcomdet_precio,
pcd.pedcomdet_cantidad*pcd.pedcomdet_precio as total
from pedido_compra_det pcd
join items i on i.itm_cod = pcd.itm_cod and i.tipitem_cod = pcd.tipitem_cod
	join tipo_item ti on ti.tipitem_cod = i.tipitem_cod
	join unidad_medida um on um.uni_cod = i.uni_cod 
order by pcd.pedcom_cod;

--v_presupuesto_prov_cab (PRESUPUESTO PROVEEDOR CABECERA)
create or replace view v_presupuesto_prov_cab as
select 
ppc.presprov_cod,
pp.pedcom_cod,
to_char(ppc.presprov_fecha, 'dd/mm/yyyy') as presprov_fecha,
to_char(ppc.presprov_fechavenci, 'dd/mm/yyyy') as presprov_fechavenci2,
ppc.presprov_fechavenci,
ppc.pro_cod,
p.pro_ruc,
p.pro_razonsocial||' - '||tp.tiprov_descripcion as pro_razonsocial,
ppc.tiprov_cod,
ppc.usu_cod,
u.usu_login,
ppc.suc_cod,
s.suc_descri,
ppc.emp_cod,
e.emp_razonsocial,
ppc.presprov_estado 
from presupuesto_prov_cab ppc
join proveedor p on p.pro_cod = ppc.pro_cod and p.tiprov_cod = ppc.tiprov_cod
	join tipo_proveedor tp on tp.tiprov_cod = p.tiprov_cod
join sucursales s on s.suc_cod = ppc.suc_cod and s.emp_cod = ppc.emp_cod
	join empresa e on e.emp_cod = s.emp_cod
join pedido_presupuesto pp on pp.presprov_cod = ppc.presprov_cod
join usuarios u on u.usu_cod = ppc.usu_cod
where ppc.presprov_estado != 'ANULADO'
order by ppc.presprov_cod;

--v_presupuesto_prov_det (PRESUPUESTO PROVEEDOR DETALLE)
create or replace view v_presupuesto_prov_det as
select 
ppd.presprov_cod,
ppd.itm_cod,
i.itm_descri,
ppd.tipitem_cod,
i.tipimp_cod,,
ppd.presprovdet_cantidad,
um.uni_descri||' ('||um.uni_simbolo||')' as uni_descri,
ppd.presprovdet_precio,
ppd.presprovdet_cantidad * ppd.presprovdet_precio as total,
(case i.tipimp_cod when 1 then ppd.presprovdet_cantidad * ppd.presprovdet_precio else 0 end) as exenta,
(case i.tipimp_cod when 2 then ppd.presprovdet_cantidad * ppd.presprovdet_precio else 0 end) as iva5,
(case i.tipimp_cod when 3 then ppd.presprovdet_cantidad * ppd.presprovdet_precio else 0 end) as iva10
from presupuesto_prov_det ppd 
join items i on i.itm_cod = ppd.itm_cod and i.tipitem_cod = ppd.tipitem_cod
	join tipo_item ti on ti.tipitem_cod = i.tipitem_cod 
	join unidad_medida um on um.uni_cod = i.uni_cod 
order by ppd.presprov_cod;


--v_orden_compra_cab (ORDEN DE COMPRAS CABECERA)
create or replace view v_orden_compra_cab as
select 
occ.ordcom_cod,
to_char(occ.ordcom_fecha, 'dd/mm/yyyy') as ordcom_fecha,
occ.ordcom_condicionpago,
occ.ordcom_cuota,
occ.ordcom_intefecha,
occ.ordcom_montocuota,
occ.pro_cod,
occ.tiprov_cod,
p.pro_razonsocial||' - '||tp.tiprov_descripcion as pro_razonsocial,
p.pro_razonsocial as proveedor,
p.pro_email,
po.presprov_cod,
pp.pedcom_cod,
occ.suc_cod,
s.suc_descri,
occ.emp_cod,
e.emp_razonsocial,
occ.usu_cod,
u.usu_login,
occ.ordcom_estado
from orden_compra_cab occ 
join proveedor p on p.pro_cod = occ.pro_cod and p.tiprov_cod = occ.tiprov_cod
	join tipo_proveedor tp on tp.tiprov_cod = p.tiprov_cod
join sucursales s on s.suc_cod = occ.suc_cod and s.emp_cod = occ.emp_cod
	join empresa e on e.emp_cod = s.emp_cod
join presupuesto_orden po on po.ordcom_cod = occ.ordcom_cod
	join pedido_presupuesto pp on pp.presprov_cod = po.presprov_cod
join usuarios u on u.usu_cod = occ.usu_cod
where occ.ordcom_estado != 'ANULADO'
order by occ.ordcom_cod;

--v_orden_compra_det (ORDEN DE COMPRAS DETALLE)
create or replace view v_orden_compra_det as
select 
ocd.ordcom_cod,
ocd.itm_cod,
ocd.tipitem_cod,
i.tipimp_cod,
i.itm_descri,
ocd.ordcomdet_cantidad,
um.uni_descri||' ('||um.uni_simbolo||')' as uni_descri,
ocd.ordcomdet_precio,
(case i.tipimp_cod when 1 then ocd.ordcomdet_cantidad * ocd.ordcomdet_precio else 0 end) as exenta,
(case i.tipimp_cod when 2 then ocd.ordcomdet_cantidad * ocd.ordcomdet_precio else 0 end) as iva5,
(case i.tipimp_cod when 3 then ocd.ordcomdet_cantidad * ocd.ordcomdet_precio else 0 end) as iva10
from orden_compra_det ocd 
join items i on i.itm_cod = ocd.itm_cod and i.tipitem_cod = ocd.tipitem_cod
	join tipo_item ti on ti.tipitem_cod = i.tipitem_cod 
	join unidad_medida um on um.uni_cod = i.uni_cod 
order by ocd.ordcom_cod;

--v_compra_cab (COMPRAS CABECEERA)
create or replace view v_compra_cab as
select 
cc.com_cod,
cc.emp_cod,
e.emp_razonsocial,
cc.suc_cod,
s.suc_descri,
cc.usu_cod,
u.usu_login,
to_char(cc.com_fecha, 'dd/mm/yyyy') as com_fecha,
cc.com_nrofac,
cc.pro_cod,
p.pro_razonsocial||' - '||tp.tiprov_descripcion as pro_razonsocial,
cc.com_timbrado,
occ.ordcom_cod,
cc.com_tipfac,
cc.com_montocuota,
cc.com_cuotas,
cc.com_intefecha,
cc.com_estado 
from compra_cab cc 
join usuarios u on u.usu_cod = cc.usu_cod
join sucursales s on s.suc_cod = cc.suc_cod and s.emp_cod = cc.emp_cod
     join empresa e on e.emp_cod = s.emp_cod
join proveedor p on p.pro_cod = cc.pro_cod and p.tiprov_cod = cc.tiprov_cod
	join tipo_proveedor tp on tp.tiprov_cod = p.tiprov_cod
join compra_orden co on co.com_cod = cc.com_cod 
	join orden_compra_cab occ on occ.ordcom_cod = co.ordcom_cod
where cc.com_estado != 'ANULADO'	
order by cc.com_cod;

--v_compra_det (COMPRAS DETALLES)
create or replace view v_compra_det as
select 
cd.com_cod,
cd.dep_cod,
d.dep_descri,
cd.itm_cod,
cd.tipitem_cod,
i.tipimp_cod,
i.itm_descri,
cd.comdet_cantidad,
cd.comdet_precio,
um.uni_descri||' ('||um.uni_simbolo||')' as uni_descri,
(case i.tipimp_cod when 1 then cd.comdet_cantidad * cd.comdet_precio else 0 end) as exenta,
(case i.tipimp_cod when 2 then cd.comdet_cantidad * cd.comdet_precio else 0 end) as iva5,
(case i.tipimp_cod when 3 then cd.comdet_cantidad * cd.comdet_precio else 0 end) as iva10
from compra_det cd 
join stock s on s.itm_cod = cd.itm_cod 
	and s.tipitem_cod = cd.tipitem_cod 
	and s.dep_cod = cd.dep_cod 
	and s.suc_cod = cd.suc_cod 
	and s.emp_cod = cd.emp_cod 
	join items i on i.itm_cod = s.itm_cod and i.tipitem_cod = s.tipitem_cod
		join tipo_item ti on ti.tipitem_cod = i.tipitem_cod
		join unidad_medida um on um.uni_cod = i.uni_cod 
	join depositos d on d.dep_cod = s.dep_cod and d.suc_cod = s.suc_cod and d.emp_cod = s.emp_cod
		join sucursales s2 on s2.suc_cod = d.dep_cod and s2.emp_cod = d.emp_cod 
			join empresa e on e.emp_cod = s2.emp_cod 
order by cd.com_cod;

--v_ajuste_invenario_cab (AJUSTE INVENTARIO CABECERA) 
create or replace view v_ajuste_invenario_cab as
select
aic.ajinv_cod,
aic.emp_cod,
e.emp_razonsocial,
aic.suc_cod,
s.suc_descri,
u.usu_login,
aic.usu_cod,
to_char(aic.ajinv_fecha ,'dd-mm-yyyy') as ajinv_fecha,
aic.ajinv_tipoajuste,
aic.ajinv_estado
from ajuste_invenario_cab aic
join usuarios u on u.usu_cod = aic.usu_cod
join sucursales s on s.suc_cod = aic.suc_cod and s.emp_cod = aic.emp_cod
     join empresa e on e.emp_cod = s.emp_cod
where aic.ajinv_estado  != 'ANULADO'
order by aic.ajinv_cod;

--v_ajuste_invenario_det (AJUSTE INVENTARIO DETALLE) 
create or replace view v_ajuste_invenario_det as
select 
aid.ajinv_cod,
aid.dep_cod,
d.dep_descri,
aid.itm_cod,
aid.tipitem_cod,
i.itm_descri,
aid.ajinvdet_cantidad,
um.uni_descri||' ('||um.uni_simbolo||')' as uni_descri,
aid.ajinvdet_precio,
aid.ajinvdet_motivo 
from ajuste_invenario_det aid
join stock s on s.itm_cod = aid.itm_cod 
	and s.tipitem_cod = aid.tipitem_cod 
	and s.dep_cod = aid.dep_cod 
	and s.suc_cod = aid.suc_cod 
	and s.emp_cod = aid.emp_cod 
	join items i on i.itm_cod = s.itm_cod and i.tipitem_cod = s.tipitem_cod
		join tipo_item ti on ti.tipitem_cod = i.tipitem_cod
		join unidad_medida um on um.uni_cod = i.uni_cod 
	join depositos d on d.dep_cod = s.dep_cod and d.suc_cod = s.suc_cod and d.emp_cod = s.emp_cod
		join sucursales s2 on s2.suc_cod = d.suc_cod and s2.emp_cod = d.emp_cod 
			join empresa e on e.emp_cod = s2.emp_cod 
order by aid.ajinv_cod;

--v_nota_compra_cab (NOTA COMPRA CABECERA) 
create or replace view v_nota_compra_cab as
select 
ncc.notacom_cod,
ncc.emp_cod,
e.emp_razonsocial,
ncc.suc_cod,
s.suc_descri,
ncc.usu_cod,
u.usu_login,
to_char(ncc.notacom_fecha, 'dd/mm/yyyy') as notacom_fecha,
ncc.tipcomp_cod,
tc.tipcomp_descri,
ncc.notacom_concepto,
ncc.pro_cod,
ncc.tiprov_cod,
p.pro_razonsocial||' - '||tp.tiprov_descripcion as pro_razonsocial,
ncc.com_cod,
cc.com_nrofac,
ncc.notacom_nronota,
ncc.notacom_estado 
from nota_compra_cab ncc
join compra_cab cc on cc.com_cod = ncc.com_cod
join tipo_comprobante tc on tc.tipcomp_cod = ncc.tipcomp_cod
join proveedor p on p.pro_cod = ncc.pro_cod and p.tiprov_cod = ncc.tiprov_cod
	join tipo_proveedor tp on tp.tiprov_cod = p.tiprov_cod
join sucursales s on s.suc_cod = ncc.suc_cod and s.emp_cod = ncc.emp_cod
	join empresa e on e.emp_cod = s.emp_cod
join usuarios u on u.usu_cod = ncc.usu_cod
where ncc.notacom_estado != 'ANULADO'
order by ncc.notacom_cod;

--v_nota_compra_det (NOTA COMPRA DETALLE)
create or replace view v_nota_compra_det as
select 
ncd.notacom_cod,
ncd.itm_cod,
ncd.tipitem_cod,
i.tipimp_cod,
i.itm_descri,
ncd.notacomdet_cantidad,
um.uni_descri||' ('||um.uni_simbolo||')' as uni_descri,
ncd.notacomdet_precio,
(case i.tipimp_cod when 1 then ncd.notacomdet_cantidad * ncd.notacomdet_precio else 0 end) as exenta,
(case i.tipimp_cod when 2 then ncd.notacomdet_cantidad * ncd.notacomdet_precio else 0 end) as iva5,
(case i.tipimp_cod when 3 then ncd.notacomdet_cantidad * ncd.notacomdet_precio else 0 end) as iva10,
ncd.dep_cod,
d.dep_descri
from nota_compra_det ncd
join stock s on s.itm_cod = ncd.itm_cod 
	and s.tipitem_cod = ncd.tipitem_cod 
	and s.dep_cod = ncd.dep_cod 
	and s.suc_cod = ncd.suc_cod 
	and s.emp_cod = ncd.emp_cod 
	join items i on i.itm_cod = ncd.itm_cod and i.tipitem_cod = ncd.tipitem_cod
		join tipo_item ti on ti.tipitem_cod = i.tipitem_cod
		join unidad_medida um on um.uni_cod = i.uni_cod 
	join depositos d on d.dep_cod = s.dep_cod and d.suc_cod = s.suc_cod and d.emp_cod = s.emp_cod
		join sucursales s2 on s2.suc_cod = d.dep_cod and s2.emp_cod = d.emp_cod 
			join empresa e on e.emp_cod = s2.emp_cod 
order by ncd.notacom_cod;


-----------------------------------------------------------TRIGGERS----------------------------------------------------------

--tg_pedido_compra_det_auditoria (Aduitoria de pedido compra detalle)
create or replace function sp_pedido_compra_det_auditoria() 
returns trigger
as $$
	-- Se declaran los codigos y nombres de usuario para la tabla de auditoria
	declare
		usu_cod_old integer := (select pcc.usu_cod from pedido_compra_cab pcc where pcc.pedcom_cod = old.pedcom_cod);
		usu_login_old varchar := (select u.usu_login from usuarios u where u.usu_cod = usu_cod_old);
		usu_cod_new integer := (select pcc.usu_cod from pedido_compra_cab pcc where pcc.pedcom_cod = new.pedcom_cod);
		usu_login_new varchar := (select u.usu_login from usuarios u where u.usu_cod = usu_cod_new);
	begin
		-- Si la operacion es eliminar el registro
	    if (TG_OP = 'DELETE') then
	        insert into pedido_compra_det_auditoria (
				pcdaudi_operacion,
				usu_cod,
				usu_login,
				itm_cod,
				tipitem_cod,
				pedcom_cod, 
				pedcomdet_cantidad, 
				pedcomdet_precio)
	        values (
				TG_OP, 
				usu_cod_old, 
				usu_login_old,
				old.itm_cod,
				old.tipitem_cod,
				old.pedcom_cod,
				old.pedcomdet_cantidad,
				old.pedcomdet_precio);
		-- Si la operacion es insertar un registro
	    elseif (TG_OP = 'INSERT') then
	        insert into pedido_compra_det_auditoria (
				pcdaudi_operacion,
				usu_cod,
				usu_login,
				itm_cod,
				tipitem_cod,
				pedcom_cod, 
				pedcomdet_cantidad, 
				pedcomdet_precio)
	        values (
				TG_OP, 
				usu_cod_new, 
				usu_login_new,
				new.itm_cod,
				new.tipitem_cod,
				new.pedcom_cod,
				new.pedcomdet_cantidad,
				new.pedcomdet_precio);
	    end if;
		-- Se retorna null para cerrar la funcion
	    return null; 
	end; 
$$
language plpgsql;

create trigger tg_pedido_compra_det_auditoria
after insert or delete on pedido_compra_det
for each row execute function sp_pedido_compra_det_auditoria();

--tg_presupuesto_prov_det_auditoria (Aduitoria de presupuesto proveedor detalle)
create or replace function sp_presupuesto_prov_det_auditoria() 
returns trigger
as $$
	-- Se declaran los codigos y nombres de usuario para la tabla de auditoria
	declare
		usu_cod_old integer := (select ppc.usu_cod from presupuesto_prov_cab ppc where ppc.presprov_cod = old.presprov_cod);
		usu_login_old varchar := (select u.usu_login from usuarios u where u.usu_cod = usu_cod_old);
		usu_cod_new integer := (select ppc.usu_cod from presupuesto_prov_cab ppc where ppc.presprov_cod = new.presprov_cod);
		usu_login_new varchar := (select u.usu_login from usuarios u where u.usu_cod = usu_cod_new);
	begin
		-- Si la operacion es eliminar el registro
	    if (TG_OP = 'DELETE') then
	        insert into presupuesto_prov_det_auditoria (
				ppdaudi_operacion,
				usu_cod,
				usu_login,
				presprov_cod, 
				itm_cod,
				tipitem_cod,
				presprovdet_cantidad, 
				presprovdet_precio)
	        values (
				TG_OP, 
				usu_cod_old, 
				usu_login_old,
				old.presprov_cod,
				old.itm_cod,
				old.tipitem_cod,
				old.presprovdet_cantidad,
				old.presprovdet_precio);
		-- Si la operacion es insertar un registro
	    elseif (TG_OP = 'INSERT') then
	        insert into presupuesto_prov_det_auditoria (
				ppdaudi_operacion,
				usu_cod,
				usu_login,
				presprov_cod, 
				itm_cod,
				tipitem_cod,
				presprovdet_cantidad, 
				presprovdet_precio)
	        values (
				TG_OP, 
				usu_cod_new, 
				usu_login_new,
				new.presprov_cod,
				new.itm_cod,
				new.tipitem_cod,
				new.presprovdet_cantidad,
				new.presprovdet_precio);
	    end if;
		-- Se retorna null para cerrar la funcion
	    return null; 
	end; 
$$
language plpgsql;

create trigger tg_presupuesto_prov_det_auditoria
after insert or delete on presupuesto_prov_det
for each row execute function sp_presupuesto_prov_det_auditoria();

--tg_orden_compra_det_auditoria (Aduitoria de orden compra detalle)
create or replace function sp_orden_compra_det_auditoria() 
returns trigger
as $$
	-- Se declaran los codigos y nombres de usuario para la tabla de auditoria
	declare
		usu_cod_old integer := (select occ.usu_cod from orden_compra_cab occ where occ.ordcom_cod = old.ordcom_cod);
		usu_login_old varchar := (select u.usu_login from usuarios u where u.usu_cod = usu_cod_old);
		usu_cod_new integer := (select occ.usu_cod from orden_compra_cab occ where occ.ordcom_cod = new.ordcom_cod);
		usu_login_new varchar := (select u.usu_login from usuarios u where u.usu_cod = usu_cod_new);
	begin
		-- Si la operacion es eliminar el registro
	    if (TG_OP = 'DELETE') then
	        insert into orden_compra_det_auditoria (
				ocdaudi_operacion,
				usu_cod,
				usu_login,
				ordcom_cod, 
				itm_cod,
				tipitem_cod,
				ordcomdet_cantidad, 
				ordcomdet_precio)
	        values (
				TG_OP, 
				usu_cod_old, 
				usu_login_old,
				old.ordcom_cod,
				old.itm_cod,
				old.tipitem_cod,
				old.ordcomdet_cantidad,
				old.ordcomdet_precio);
		-- Si la operacion es insertar un registro
	    elseif (TG_OP = 'INSERT') then
	        insert into orden_compra_det_auditoria (
				ocdaudi_operacion,
				usu_cod,
				usu_login,
				ordcom_cod, 
				itm_cod,
				tipitem_cod,
				ordcomdet_cantidad, 
				ordcomdet_precio)
	        values (
				TG_OP, 
				usu_cod_new, 
				usu_login_new,
				new.ordcom_cod,
				new.itm_cod,
				new.tipitem_cod,
				new.ordcomdet_cantidad,
				new.ordcomdet_precio);
	    end if;
		-- Se retorna null para cerrar la funcion
	    return null; 
	end; 
$$
language plpgsql;

create trigger tg_orden_compra_det_auditoria
after insert or delete on orden_compra_det
for each row execute function sp_orden_compra_det_auditoria();

--tg_compra_det_auditoria (Aduitoria de compra detalle)
create or replace function sp_compra_det_auditoria() 
returns trigger
as $$
	-- Se declaran los codigos y nombres de usuario para la tabla de auditoria
	declare
		usu_cod_old integer := (select cc.usu_cod from compra_cab cc where cc.com_cod = old.com_cod);
		usu_login_old varchar := (select u.usu_login from usuarios u where u.usu_cod = usu_cod_old);
		usu_cod_new integer := (select cc.usu_cod from compra_cab cc where cc.com_cod = new.com_cod);
		usu_login_new varchar := (select u.usu_login from usuarios u where u.usu_cod = usu_cod_new);
	begin
		-- Si la operacion es eliminar el registro
	    if (TG_OP = 'DELETE') then
	        insert into compra_det_auditoria (
				cdaudi_operacion,
				usu_cod,
				usu_login,
				com_cod, 
				itm_cod,
				tipitem_cod,
				dep_cod,
				suc_cod,
				emp_cod,
				comdet_cantidad, 
				comdet_precio)
	        values (
				TG_OP, 
				usu_cod_old, 
				usu_login_old,
				old.com_cod,
				old.itm_cod,
				old.tipitem_cod,
				old.dep_cod,
				old.suc_cod,
				old.emp_cod,
				old.comdet_cantidad,
				old.comdet_precio);
		-- Si la operacion es insertar un registro
	    elseif (TG_OP = 'INSERT') then
	        insert into compra_det_auditoria (
				cdaudi_operacion,
				usu_cod,
				usu_login,
				com_cod, 
				itm_cod,
				tipitem_cod,
				dep_cod,
				suc_cod,
				emp_cod,
				comdet_cantidad, 
				comdet_precio)
	        values (
				TG_OP, 
				usu_cod_new, 
				usu_login_new,
				new.com_cod,
				new.itm_cod,
				new.tipitem_cod,
				new.dep_cod,
				new.suc_cod,
				new.emp_cod,
				new.comdet_cantidad,
				new.comdet_precio);
	    end if;
		-- Se retorna null para cerrar la funcion
	    return null; 
	end; 
$$
language plpgsql;

create trigger tg_compra_det_auditoria
after insert or delete on compra_det
for each row execute function sp_compra_det_auditoria();

--tg_ajuste_inventario_det_auditoria (Aduitoria de ajuste inventario detalle)
create or replace function sp_ajuste_inventario_det_auditoria() 
returns trigger
as $$
	-- Se declaran los codigos y nombres de usuario para la tabla de auditoria
	declare
		usu_cod_old integer := (select aic.usu_cod from ajuste_inventario_cab aic where aic.ajinv_cod = old.ajinv_cod);
		usu_login_old varchar := (select u.usu_login from usuarios u where u.usu_cod = usu_cod_old);
		usu_cod_new integer := (select aic.usu_cod from ajuste_inventario_cab aic where aic.ajinv_cod = new.ajinv_cod);
		usu_login_new varchar := (select u.usu_login from usuarios u where u.usu_cod = usu_cod_new);
	begin
		-- Si la operacion es eliminar el registro
	    if (TG_OP = 'DELETE') then
	        insert into ajuste_inventario_det_auditoria (
				aidaudi_operacion,
				usu_cod,
				usu_login,
				ajinv_cod, 
				itm_cod,
				tipitem_cod,
				dep_cod,
				suc_cod,
				emp_cod,
				ajinvdet_motivo,
				ajinvdet_cantidad, 
				ajinvdet_precio)
	        values (
				TG_OP, 
				usu_cod_old, 
				usu_login_old,
				old.ajinv_cod,
				old.itm_cod,
				old.tipitem_cod,
				old.dep_cod,
				old.suc_cod,
				old.emp_cod,
				old.ajinvdet_motivo,
				old.ajinvdet_cantidad,
				old.ajinvdet_precio);
		-- Si la operacion es insertar un registro
	    elseif (TG_OP = 'INSERT') then
	        insert into ajuste_inventario_det_auditoria (
				aidaudi_operacion,
				usu_cod,
				usu_login,
				ajinv_cod, 
				itm_cod,
				tipitem_cod,
				dep_cod,
				suc_cod,
				emp_cod,
				ajinvdet_motivo,
				ajinvdet_cantidad, 
				ajinvdet_precio)
	        values (
				TG_OP, 
				usu_cod_new, 
				usu_login_new,
				new.ajinv_cod,
				new.itm_cod,
				new.tipitem_cod,
				new.dep_cod,
				new.suc_cod,
				new.emp_cod,
				new.ajinvdet_motivo,
				new.ajinvdet_cantidad,
				new.ajinvdet_precio);
	    end if;
		-- Se retorna null para cerrar la funcion
	    return null; 
	end; 
$$
language plpgsql;

create trigger tg_ajuste_inventario_det_auditoria
after insert or delete on ajuste_inventario_det
for each row execute function sp_ajuste_inventario_det_auditoria();

--tg_nota_compra_det_auditoria (Aduitoria de nota_compra_detalle detalle)
create or replace function sp_nota_compra_det_auditoria() 
returns trigger
as $$
	-- Se declaran los codigos y nombres de usuario para la tabla de auditoria
	declare
		usu_cod_old integer := (select ncc.usu_cod from nota_compra_cab ncc where ncc.com_cod = old.notacom_cod);
		usu_login_old varchar := (select u.usu_login from usuarios u where u.usu_cod = usu_cod_old);
		usu_cod_new integer := (select ncc.usu_cod from nota_compra_cab ncc where ncc.com_cod = new.notacom_cod);
		usu_login_new varchar := (select u.usu_login from usuarios u where u.usu_cod = usu_cod_new);
	begin
		-- Si la operacion es eliminar el registro
	    if (TG_OP = 'DELETE') then
	        insert into nota_compra_det_auditoria (
				ncdaudi_operacion,
				usu_cod,
				usu_login,
				notacom_cod, 
				itm_cod,
				tipitem_cod,
				dep_cod,
				suc_cod,
				emp_cod,
				notacomdet_cantidad, 
				notacomdet_precio)
	        values (
				TG_OP, 
				usu_cod_old, 
				usu_login_old,
				old.notacom_cod,
				old.itm_cod,
				old.tipitem_cod,
				old.dep_cod,
				old.suc_cod,
				old.emp_cod,
				old.notacomdet_cantidad,
				old.notacomdet_precio);
		-- Si la operacion es insertar un registro
	    elseif (TG_OP = 'INSERT') then
	        insert into nota_compra_det_auditoria (
				ncdaudi_operacion,
				usu_cod,
				usu_login,
				notacom_cod, 
				itm_cod,
				tipitem_cod,
				dep_cod,
				suc_cod,
				emp_cod,
				notacomdet_cantidad, 
				notacomdet_precio)
	        values (
				TG_OP, 
				usu_cod_new, 
				usu_login_new,
				new.notacom_cod,
				new.itm_cod,
				new.tipitem_cod,
				new.dep_cod,
				new.suc_cod,
				new.emp_cod,
				new.notacomdet_cantidad,
				new.notacomdet_precio);
	    end if;
		-- Se retorna null para cerrar la funcion
	    return null; 
	end; 
$$
language plpgsql;

create trigger tg_nota_compra_det_auditoria
after insert or delete on nota_compra_det
for each row execute function sp_nota_compra_det_auditoria();

--tg_stock_auditoria (Aduitoria de stock )
create or replace function sp_stock_auditoria() 
returns trigger
as $$
	begin
		-- Si la operacion es actualizar el registro
	    if (TG_OP = 'UPDATE') then
			--ANTERIOR
	        insert into stock_auditoria (
				stoaudi_operacion,
				itm_cod,
				tipitem_cod,
				dep_cod, 
				suc_cod,
				emp_cod,
				sto_cantidad)
	        values (
				TG_OP||' - REG. ANTERIOR', 
				old.itm_cod,
				old.tipitem_cod,
				old.dep_cod, 
				old.suc_cod,
				old.emp_cod,
				old.sto_cantidad);
			--NUEVO
	        insert into stock_auditoria (
				stoaudi_operacion,
				itm_cod,
				tipitem_cod,
				dep_cod, 
				suc_cod,
				emp_cod,
				sto_cantidad)
	        values (
				TG_OP||' - REG. NUEVO', 
				new.itm_cod,
				new.tipitem_cod,
				new.dep_cod, 
				new.suc_cod,
				new.emp_cod,
				new.sto_cantidad);
		-- Si la operacion es insertar un registro
	    elseif (TG_OP = 'INSERT') then
	        insert into stock_auditoria (
				stoaudi_operacion,
				itm_cod,
				tipitem_cod,
				dep_cod, 
				suc_cod,
				emp_cod,
				sto_cantidad)
	        values (
				TG_OP, 
				new.itm_cod,
				new.tipitem_cod,
				new.dep_cod, 
				new.suc_cod,
				new.emp_cod,
				new.sto_cantidad);
	    end if;
		-- Se retorna null para cerrar la funcion
	    return null; 
	end; 
$$
language plpgsql;

create trigger tg_stock_auditoria
after insert or update on stock
for each row execute function sp_stock_auditoria();
