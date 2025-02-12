-------------------------------REFERENCIALES-------------------------------

--sp_abm_personas (PERSONAS)
CREATE OR REPLACE FUNCTION sp_abm_personas
(percod integer, 
pernombres varchar, 
perapellidos varchar,
pernrodoc varchar,
pertelefono varchar,
peremail varchar,
tipdoccod integer,
perestado varchar,
operacion integer,
usucod integer,
usulogin varchar,
transaccion varchar,
tipdocdescri varchar)
RETURNS void
AS $$
declare peraudit text;
begin --iniciar
	--se designan validaciones
	if operacion in (1,2) then
		perform * from personas
		where per_nrodoc = pernrodoc and per_cod != percod;
		if found then
			raise exception '1';
		elseif operacion = 1 then --realizamos un insert
			INSERT INTO public.personas 
			(per_cod, 
			per_nombres, 
			per_apellidos,
			per_nrodoc,
			per_telefono,
			per_email,
			tipdoc_cod,
			per_estado)
			VALUES(
			percod, 
			upper(pernombres),
			upper(perapellidos),
			pernrodoc,
			pertelefono,
			peremail,
			tipdoccod,
			'ACTIVO');
			raise notice 'LA PERSONA FUE REGISTRADA CON EXITO';
		elseif operacion = 2 then -- realizamos un update 
			UPDATE public.personas  
			SET per_nombres=upper(pernombres), 
			per_apellidos=upper(perapellidos),
			per_nrodoc = pernrodoc,
			per_telefono = pertelefono,
			per_email = peremail,
			tipdoc_cod = tipdoccod,
			per_estado='ACTIVO'
			WHERE per_cod=percod;
			raise notice 'LA PERSONA FUE MODIFICADA CON EXITO';
		end if;
	end if;
	if operacion = 3 then -- realizamos un update 
		update personas
		set per_estado = 'INACTIVO'
		WHERE per_cod = percod ;
		raise notice 'LA PERSONA FUE BORRADA CON EXITO';
	end if;
	--se selecciona la ultima auditoria
	select coalesce(per_audit,'') into peraudit
	from personas
	where per_cod = percod;

	--se actualiza la auditoria
	update personas
    set per_audit = peraudit||' '||json_build_object(
        'usu_cod', usucod,
		'usu_login', usulogin,
        'fecha y hora', to_char(current_timestamp,'dd-mm-yyyy hh24:mi:ss'),
        'transaccion', upper(transaccion),
		'per_nombres', upper(pernombres), 
		'per_apellidos', upper(perapellidos), 
		'per_nrodoc', pernrodoc,
		'tipdoc_cod', tipdoccod,
		'tipdoc_descri', upper(tipdocdescri), 
		'per_telefono', pertelefono,
		'per_email', peremail,
		'per_estado', upper(perestado)
    )||','
    WHERE per_cod = percod;
end--finalizar
$$
language plpgsql;


--sp_abm_funcionarios (FUNCIONARIOS)
CREATE OR REPLACE FUNCTION sp_abm_funcionarios
(funcod integer, 
funfechaingreso date, 
funestado varchar,
percod integer,
ciucod integer,
carcod integer,
succod integer,
empcod integer,
operacion integer,
usucod integer,
usulogin varchar,
transaccion varchar,
nombre varchar,
ciudescripcion varchar,
cardescri varchar,
sucdescri varchar,
emprazonsocial varchar)
RETURNS void
AS $$
declare funaudit text;
begin --iniciar
	--se designan validaciones
	if operacion in (1,2) then
		perform * from funcionarios
		where per_cod = percod and fun_cod != funcod;
		if found then
			raise exception '1';
		elseif operacion = 1 then --realizamos un insert
			INSERT INTO public.funcionarios
			(fun_cod, 
			fun_fechaingreso, 
			per_cod,
			ciu_cod,
			car_cod,
			suc_cod,
			emp_cod,
			fun_estado)
			VALUES(
			funcod, 
			funfechaingreso,
			percod,
			ciucod,
			carcod,
			succod,
			empcod,
			'ACTIVO');
			raise notice 'EL FUNCIONARIO FUE REGISTRADO CON EXITO';
		elseif operacion = 2 then -- realizamos un update 
			UPDATE public.funcionarios 
			SET fun_fechaingreso = funfechaingreso, 
			per_cod = percod,
			ciu_cod = ciucod,
			car_cod = carcod,
			suc_cod = succod,
			emp_cod = empcod,
			fun_estado ='ACTIVO'
			WHERE fun_cod = funcod;
			raise notice 'EL FUNCIONARIO FUE MODIFICADO CON EXITO';
		end if;
	end if;
	if operacion = 3 then -- realizamos un update 
		update funcionarios
		set fun_estado = 'INACTIVO'
		WHERE fun_cod = percod ;
		raise notice 'EL FUNCIONARIO FUE BORRADO CON EXITO';
	end if;
	--se selecciona la ultima auditoria
	select coalesce(fun_audit,'') into funaudit
	from funcionarios
	where fun_cod = funcod;

	--se actualiza la auditoria
	update funcionarios
    set fun_audit = funaudit||' '||json_build_object(
        'usu_cod', usucod,
		'usu_login', usulogin,
        'fecha y hora', to_char(current_timestamp,'dd-mm-yyyy hh24:mi:ss'),
        'transaccion', upper(transaccion),
		'fun_fechaingreso', to_char(funfechaingreso,'dd-mm-yyyy'),
        'per_cod', percod,
		'nombre', upper(nombre), 
		'ciu_cod', ciucod,  
		'ciu_descripcion', upper(ciudescripcion), 
		'suc_cod', succod,  
		'suc_descri', upper(sucdescri), 
		'emp_cod', empcod,  
		'emp_razonsocial', upper(emprazonsocial), 
		'fun_estado', upper(funestado)
    )||','
    WHERE fun_cod = funcod;
end--finalizar
$$
language plpgsql;

--sp_abm_cargos (CARGOS)
CREATE OR REPLACE FUNCTION sp_abm_cargos
(carcod integer, 
cardescri varchar, 
carestado varchar,
operacion integer,
usucod integer,
usulogin varchar,
transaccion varchar)
RETURNS void
AS $$
declare caraudit text;
begin --iniciar
	--se designan validaciones
	if operacion in (1,2) then
		perform * from cargos
		where car_descri = upper(cardescri) and car_cod != carcod;
		if found then
			raise exception '1';
		elseif operacion = 1 then --realizamos un insert
			INSERT INTO public.cargos 
			(car_cod, 
			car_descri, 
			car_estado)
			VALUES(
			carcod, 
			upper(cardescri), 
			'ACTIVO');
			raise notice 'EL CARGO FUE REGISTRADO CON EXITO';
		elseif operacion = 2 then -- realizamos un update 
			UPDATE public.cargos
			SET car_descri = upper(cardescri), 
			car_estado ='ACTIVO'
			WHERE car_cod = carcod;
			raise notice 'EL CARGO FUE MODIFICADO CON EXITO';
		end if;
	end if;
	if operacion = 3 then -- realizamos un update 
		update cargos
		set car_estado = 'INACTIVO'
		WHERE car_cod = carcod;
		raise notice 'EL CARGO FUE BORRADO CON EXITO';
	end if;
	--se selecciona la ultima auditoria
	select coalesce(car_audit,'') into caraudit
	from cargos
	where car_cod = carcod;

	--se actualiza la auditoria
	update cargos
    set car_audit = caraudit||' '||json_build_object(
        'usu_cod', usucod,
		'usu_login', usulogin,
        'fecha y hora', to_char(current_timestamp,'dd-mm-yyyy hh24:mi:ss'),
        'transaccion', upper(transaccion),
		'car_descri', upper(cardescri), 
		'car_estado', upper(carestado)
    )||','
    WHERE car_cod = carcod;
end--finalizar
$$
language plpgsql;

--sp_abm_dias (DIAS)
CREATE OR REPLACE FUNCTION sp_abm_dias
(diacod integer, 
diadescri varchar, 
diaestado varchar, 
operacion integer,
usucod integer,
usulogin varchar,
transaccion varchar)
RETURNS void
AS $$
declare diaaudit text;
begin --iniciar
	--se designan validaciones
	if operacion in (1,2) then
		perform * from dias
		where dia_descri = upper(diadescri) and dia_cod != diacod;
		if found then
			raise exception '1';
		elseif operacion = 1 then --realizamos un insert
			INSERT INTO dias
			(dia_cod, 
			dia_descri, 
			dia_estado)
			VALUES(
			diacod, 
			upper(diadescri), 
			'ACTIVO');
			raise notice 'EL DIA FUE REGISTRADO CON EXITO';
		elseif operacion = 2 then -- realizamos un update 
			UPDATE dias
			SET dia_descri = upper(diadescri), 
			dia_estado = 'ACTIVO'
			WHERE dia_cod = diacod;
			raise notice 'EL DIA FUE MODIFICADO CON EXITO';
		end if;
	end if;
	if operacion = 3 then -- realizamos un update 
		update dias
		set dia_estado = 'INACTIVO'
		WHERE dia_cod = diacod ;
		raise notice 'EL DIA FUE BORRADO CON EXITO';
	end if;
	--se selecciona la ultima auditoria
	select coalesce(dia_audit,'') into diaaudit
	from dias
	where dia_cod = diacod;

	--se actualiza la auditoria
	update dias
    set dia_audit = diaaudit||' '||json_build_object(
        'usu_cod', usucod,
		'usu_login', usulogin,
        'fecha y hora', to_char(current_timestamp,'dd-mm-yyyy hh24:mi:ss'),
        'transaccion', upper(transaccion),
		'dia_descri', upper(diadescri), 
		'dia_estado', upper(diaestado)
    )||','
    WHERE dia_cod = diacod;
end--finalizar
$$
language plpgsql;

--sp_abm_tipoEquipos (TIPO EQUIPOS)
CREATE OR REPLACE FUNCTION sp_abm_tipoEquipos
(tipequicod integer, 
tipequidescri varchar, 
tipequiestado varchar,
operacion integer,
usucod integer,
usulogin varchar,
transaccion varchar)
RETURNS void
AS $$
declare tipequiaudit text;
begin --iniciar
	--se designan validaciones
	if operacion in (1,2) then
		perform * from tipo_equipos
		where tipequi_descri = upper(tipequidescri) and tipequi_cod != tipequicod;
		if found then
			raise exception '1';
		elseif operacion = 1 then --realizamos un insert
			INSERT INTO tipo_equipos
			(tipequi_cod, 
			tipequi_descri, 
			tipequi_estado)
			VALUES(
			tipequicod, 
			upper(tipequidescri), 
			'ACTIVO');
			raise notice 'EL TIPO DE EQUIPO FUE REGISTRADO CON EXITO';
		elseif operacion = 2 then -- realizamos un update 
			UPDATE tipo_equipos 
			SET tipequi_descri = upper(tipequidescri), 
			tipequi_estado ='ACTIVO'
			WHERE tipequi_cod = tipequicod;
			raise notice 'EL TIPO DE EQUIPO FUE MODIFICADO CON EXITO';
		end if;
	end if;
	if operacion = 3 then -- realizamos un update 
		update tipo_equipos
		set tipequi_estado = 'INACTIVO'
		WHERE tipequi_cod = tipequicod ;
		raise notice 'EL TIPO DE EQUIPO FUE BORRADO CON EXITO';
	end if;
	--se selecciona la ultima auditoria
	select coalesce(tipequi_audit,'') into tipequiaudit
	from tipo_equipos
	where tipequi_cod = tipequicod;

	--se actualiza la auditoria
	update tipo_equipos
    set tipequi_audit = tipequiaudit||' '||json_build_object(
        'usu_cod', usucod,
		'usu_login', usulogin,
        'fecha y hora', to_char(current_timestamp,'dd-mm-yyyy hh24:mi:ss'),
        'transaccion', upper(transaccion),
		'tipequi_descri', upper(tipequidescri), 
		'tipequi_estado', upper(tipequiestado)
    )||','
    WHERE tipequi_cod = tipequicod;
end--finalizar
$$
language plpgsql;


--sp_abm_equipos (EQUIPOS)
CREATE OR REPLACE FUNCTION sp_abm_equipos
(equicod integer, 
tipequicod integer, 
equidescri varchar,
equiestado varchar,
operacion integer,
usucod integer,
usulogin varchar,
transaccion varchar,
tipequidescri varchar)
RETURNS void
AS $$
declare equiaudit text;
begin --iniciar
	--se designan validaciones
	if operacion in (1,2) then
		perform * from equipos
		where equi_descri = upper(equidescri) and equi_cod != equicod;
		if found then
			raise exception '1';
		elseif operacion = 1 then --realizamos un insert
			INSERT INTO equipos
			(equi_cod, 
			tipequi_cod, 
			equi_descri,
			equi_estado)
			VALUES(
			(select coalesce (max(equi_cod),0)+1 from equipos), 
			tipequicod,
			upper(equidescri), 
			'ACTIVO');
			raise notice 'EL EQUIPO FUE REGISTRADO CON EXITO';
		elseif operacion = 2 then -- realizamos un update 
			UPDATE equipos
			SET tipequi_cod = tipequicod,
			equi_descri = upper(equidescri), 
			equi_estado = 'ACTIVO'
			WHERE equi_cod = equicod;
			raise notice 'EL EQUIPO FUE MODIFICADO CON EXITO';
		end if;
	end if;
	if operacion = 3 then -- realizamos un update 
		update equipos 
		set equi_estado = 'INACTIVO'
		WHERE equi_cod = equicod ;
		raise notice 'EL EQUIPO FUE BORRADO CON EXITO';
	end if;
	--se selecciona la ultima auditoria
	select coalesce(equi_audit,'') into equiaudit
	from equipos
	where equi_cod = equicod;

	--se actualiza la auditoria
	update equipos
    set equi_audit = equiaudit||' '||json_build_object(
        'usu_cod', usucod,
		'usu_login', usulogin,
        'fecha y hora', to_char(current_timestamp,'dd-mm-yyyy hh24:mi:ss'),
        'transaccion', upper(transaccion),
        'tipequi_cod', tipequicod,
		'tipequi_descri', upper(tipequidescri), 
		'equi_descri', upper(equidescri), 
		'equi_estado', upper(equiestado)
    )||','
    WHERE equi_cod = equicod;
end--finalizar
$$
language plpgsql;

--sp_abm_ejercicios (EJERCICOS)
CREATE OR REPLACE FUNCTION sp_abm_ejercicios
(ejercod integer, 
ejerdescri varchar, 
ejerestado varchar, 
operacion integer,
usucod integer,
usulogin varchar,
transaccion varchar)
RETURNS void
AS $$
declare ejeraudit text;
begin --iniciar
	--se designan validaciones
	if operacion in (1,2) then
		perform * from ejercicios
		where ejer_descri = upper(ejerdescri) and ejer_cod != ejercod;
		if found then
			raise exception '1';
		elseif operacion = 1 then --realizamos un insert
			INSERT INTO ejercicios
			(ejer_cod, 
			ejer_descri, 
			ejer_estado)
			VALUES(
			ejercod, 
			upper(ejerdescri), 
			'ACTIVO');
			raise notice 'EL EJERCICO FUE REGISTRADO CON EXITO';
		elseif operacion = 2 then -- realizamos un update 
			UPDATE ejercicios
			SET ejer_descri = upper(ejerdescri), 
			ejer_estado = 'ACTIVO'
			WHERE ejer_cod = ejercod;
			raise notice 'EL EJERCICO FUE MODIFICADO CON EXITO';
		end if;
	end if;
	if operacion = 3 then -- realizamos un update 
		update ejercicios
		set ejer_estado = 'INACTIVO'
		WHERE ejer_cod = ejercod ;
		raise notice 'EL EJERCICO FUE BORRADO CON EXITO';
	end if;
	--se selecciona la ultima auditoria
	select coalesce(ejer_audit,'') into ejeraudit
	from ejercicios
	where ejer_cod = ejercod;

	--se actualiza la auditoria
	update ejercicios
    set ejer_audit = ejeraudit||' '||json_build_object(
        'usu_cod', usucod,
		'usu_login', usulogin,
        'fecha y hora', to_char(current_timestamp,'dd-mm-yyyy hh24:mi:ss'),
        'transaccion', upper(transaccion),
		'ejer_descri', upper(ejerdescri), 
		'ejer_estado', upper(ejerestado)
    )||','
    WHERE ejer_cod = ejercod;
end--finalizar
$$
language plpgsql;

--sp_abm_tipoPlanALim (TIPO PLAN ALIMENTICIO)
CREATE OR REPLACE FUNCTION sp_abm_tipoPlanALim
(tiplancod integer, 
tiplandescri varchar, 
tiplanestado varchar, 
operacion integer,
usucod integer,
usulogin varchar,
transaccion varchar)
RETURNS void
AS $$
declare tiplanaudit text;
begin --iniciar
	--se designan validaciones
	if operacion in (1,2) then
		perform * from tipo_plan_alimenticio
		where tiplan_descri = upper(tiplandescri) and tiplan_cod != tiplancod;
		if found then
			raise exception '1';
		elseif operacion = 1 then --realizamos un insert
			INSERT INTO tipo_plan_alimenticio
			(tiplan_cod, 
			tiplan_descri, 
			tiplan_estado)
			VALUES(
			tiplancod, 
			upper(tiplandescri), 
			'ACTIVO');
			raise notice 'EL TIPO DE PLAN DE ALIMENTICIO FUE REGISTRADO CON EXITO';
		elseif operacion = 2 then -- realizamos un update 
			UPDATE tipo_plan_alimenticio
			SET tiplan_descri = upper(tiplandescri), 
			tiplan_estado = 'ACTIVO'
			WHERE tiplan_cod = tiplancod;
			raise notice 'EL TIPO DE PLAN DE ALIMENTICIO FUE MODIFICADO CON EXITO';
		end if;
	end if;
	if operacion = 3 then -- realizamos un update 
		update tipo_plan_alimenticio
		set tiplan_estado = 'INACTIVO'
		WHERE tiplan_cod = tiplancod;
		raise notice 'EL TIPO DE PLAN DE ALIMENTICIO FUE BORRADO CON EXITO';
	end if;
	--se selecciona la ultima auditoria
	select coalesce(tiplan_audit,'') into tiplanaudit
	from tipo_plan_alimenticio
	where tiplan_cod = tiplancod;

	--se actualiza la auditoria
	update tipo_plan_alimenticio
    set tiplan_audit = tiplanaudit||' '||json_build_object(
        'usu_cod', usucod,
		'usu_login', usulogin,
        'fecha y hora', to_char(current_timestamp,'dd-mm-yyyy hh24:mi:ss'),
        'transaccion', upper(transaccion),
		'tiplan_descri', upper(tiplandescri), 
		'tiplan_estado', upper(tiplanestado)
    )||','
    WHERE tiplan_cod = tiplancod;
end--finalizar
$$
language plpgsql;


--sp_abm_tipoRutinas (TIPO RUTINAS)
CREATE OR REPLACE FUNCTION sp_abm_tipoRutinas
(tiprutcod integer, 
tiprutdescri varchar, 
tiprutestado varchar, 
operacion integer,
usucod integer,
usulogin varchar,
transaccion varchar)
RETURNS void
AS $$
declare tiprutaudit text;
begin --iniciar
	--se designan validaciones
	if operacion in (1,2) then
		perform * from tipo_rutinas
		where tiprut_descri = upper(tiprutdescri) and tiprut_cod != tiprutcod;
		if found then
			raise exception '1';
		elseif operacion = 1 then --realizamos un insert
			INSERT INTO tipo_rutinas
			(tiprut_cod, 
			tiprut_descri, 
			tiprut_estado)
			VALUES(
			tiprutcod, 
			upper(tiprutdescri), 
			'ACTIVO');
			raise notice 'EL TIPO DE RUTINA FUE REGISTRADO CON EXITO';
		elseif operacion = 2 then -- realizamos un update 
			UPDATE tipo_rutinas
			SET tiprut_descri = upper(tiprutdescri), 
			tiprut_estado = 'ACTIVO'
			WHERE tiprut_cod = tiprutcod;
			raise notice 'EL TIPO DE RUTINA FUE MODIFICADO CON EXITO';
		end if;
	end if;
	if operacion = 3 then -- realizamos un update 
		update tipo_rutinas
		set tiprut_estado = 'INACTIVO'
		WHERE tiprut_cod = tiprutcod;
		raise notice 'EL TIPO DE RUTINA FUE BORRADO CON EXITO';
	end if;
	--se selecciona la ultima auditoria
	select coalesce(tiprut_audit,'') into tiprutaudit
	from tipo_rutinas
	where tiprut_cod = tiprutcod;

	--se actualiza la auditoria
	update tipo_rutinas
    set tiprut_audit = tiprutaudit||' '||json_build_object(
        'usu_cod', usucod,
		'usu_login', usulogin,
        'fecha y hora', to_char(current_timestamp,'dd-mm-yyyy hh24:mi:ss'),
        'transaccion', upper(transaccion),
		'tiprut_descri', upper(tiprutdescri), 
		'tiprut_estado', upper(tiprutestado)
    )||','
    WHERE tiprut_cod = tiprutcod;
end--finalizar
$$
language plpgsql;


--sp_abm_comidas (COMIDAS)
CREATE OR REPLACE FUNCTION sp_abm_comidas
(comicod integer, 
comidescri varchar, 
comiestado varchar, 
operacion integer,
usucod integer,
usulogin varchar,
transaccion varchar)
RETURNS void
AS $$
declare comiaudit text;
begin --iniciar
	--se designan validaciones
	if operacion in (1,2) then
		perform * from comidas
		where comi_descri = upper(comidescri) and comi_cod != comicod;
		if found then
			raise exception '1';
		elseif operacion = 1 then --realizamos un insert
			INSERT INTO comidas
			(comi_cod, 
			comi_descri, 
			comi_estado)
			VALUES(
			comicod, 
			upper(comidescri), 
			'ACTIVO');
			raise notice 'LA COMIDA FUE REGISTRADA CON 	EXITO';
		elseif operacion = 2 then -- realizamos un update 
			UPDATE comidas
			SET comi_descri = upper(comidescri), 
			comi_estado = 'ACTIVO'
			WHERE comi_cod = comicod;
			raise notice 'LA COMIDA FUE MODIFICADA CON EXITO';
		end if;
	end if;
	if operacion = 3 then -- realizamos un update 
		update comidas
		set comi_estado = 'INACTIVO'
		WHERE comi_cod = comicod;
		raise notice 'LA COMIDA FUE BORRADA CON EXITO';
	end if;
	--se selecciona la ultima auditoria
	select coalesce(comi_audit,'') into comiaudit
	from comidas
	where comi_cod = comicod;

	--se actualiza la auditoria
	update comidas
    set comi_audit = comiaudit||' '||json_build_object(
        'usu_cod', usucod,
		'usu_login', usulogin,
        'fecha y hora', to_char(current_timestamp,'dd-mm-yyyy hh24:mi:ss'),
        'transaccion', upper(transaccion),
		'comi_descri', upper(comidescri), 
		'comi_estado', upper(comiestado)
    )||','
    WHERE comi_cod = comicod;
end--finalizar
$$
language plpgsql;

--sp_abm_parametrosMedicion (PARAMETROS MEDICION)
CREATE OR REPLACE FUNCTION sp_abm_parametrosMedicion
(paramcod integer, 
paramdescri varchar, 
paramestado varchar, 
unicod integer,
paramformula varchar,
operacion integer,
usucod integer,
usulogin varchar,
unidescri varchar,
transaccion varchar)
RETURNS void
AS $$
declare paramaudit text;
begin --iniciar
	--se designan validaciones
	if operacion in (1,2) then
		perform * from parametros_medicion
		where param_descri = upper(paramdescri) and param_cod != paramcod;
		if found then
			raise exception '1';
		elseif operacion = 1 then --realizamos un insert
			INSERT INTO parametros_medicion
				(param_cod, 
				param_descri, 
				param_estado,
				uni_cod,
				param_formula)
			VALUES
				(paramcod, 
				upper(paramdescri), 
				'ACTIVO',
				unicod,
				paramformula);
			raise notice 'EL PARAMETRO DE MEDICION FUE REGISTRADO CON EXITO';
		elseif operacion = 2 then -- realizamos un update 
			UPDATE parametros_medicion
				SET param_descri = upper(paramdescri), 
				uni_cod = unicod,
				param_formula = upper(paramformula),
				param_estado = 'ACTIVO'
			WHERE param_cod = paramcod;
			raise notice 'EL PARAMETRO DE MEDICION FUE MODIFICADO CON EXITO';
		end if;
	end if;
	if operacion = 3 then -- realizamos un update 
		update parametros_medicion
			set param_estado = 'INACTIVO'
		WHERE param_cod = paramcod;
		raise notice 'EL PARAMETRO DE MEDICION FUE BORRADO CON EXITO';
	end if;
	--se selecciona la ultima auditoria
	select coalesce(param_audit,'') into paramaudit
	from parametros_medicion
	where param_cod = paramcod;

	--se actualiza la auditoria
	update parametros_medicion
    set param_audit = paramaudit||' '||json_build_object(
        'usu_cod', usucod,
		'usu_login', usulogin,
        'fecha y hora', to_char(current_timestamp,'dd-mm-yyyy hh24:mi:ss'),
        'transaccion', upper(transaccion),
		'param_descri', upper(paramdescri), 
		'param_formula', upper(paramformula),
        'uni_cod', unicod,
		'uni_descri', upper(unidescri),
		'param_estado', upper(paramestado)
    )||','
    WHERE param_cod = paramcod;
end--finalizar
$$
language plpgsql;

--sp_abm_unidadMedida (UNIDAD MEDIDA)
CREATE OR REPLACE FUNCTION sp_abm_unidadMedida
(unicod integer, 
unidescri varchar, 
uniestado varchar, 
unisimbolo varchar,
operacion integer,
usucod integer,
usulogin varchar,
transaccion varchar)
RETURNS void
AS $$
declare uniaudit text;
begin --iniciar
	--se designan validaciones
	if operacion in (1,2) then
		perform * from unidad_medida
		where (uni_descri = upper(unidescri) or uni_simbolo = upper(unisimbolo)) and uni_cod != unicod;
		if found then
			raise exception '1';
		elseif operacion = 1 then --realizamos un insert
			INSERT INTO unidad_medida
			(uni_cod, 
			uni_descri, 
			uni_estado,
			uni_simbolo)
			VALUES(
			unicod, 
			upper(unidescri), 
			'ACTIVO',
			upper(unisimbolo));
			raise notice 'LA UNIDAD DE MEDIDA FUE REGISTRADA CON EXITO';
		elseif operacion = 2 then -- realizamos un update 
			UPDATE unidad_medida
			SET uni_descri = upper(unidescri), 
			uni_estado = 'ACTIVO',
			uni_simbolo = upper(unisimbolo)
			WHERE uni_cod = unicod;
			raise notice 'LA UNIDAD DE MEDIDA FUE MODIFICADA CON EXITO';
		end if;
	end if;
	if operacion = 3 then -- realizamos un update 
		update unidad_medida
		set uni_estado = 'INACTIVO'
		WHERE uni_cod = unicod;
		raise notice 'LA UNIDAD DE MEDIDA FUE BORRADA CON EXITO';
	end if;
	--se selecciona la ultima auditoria
	select coalesce(uni_audit,'') into uniaudit
	from unidad_medida
	where uni_cod = unicod;

	--se actualiza la auditoria
	update unidad_medida
    set uni_audit = uniaudit||' '||json_build_object(
        'usu_cod', usucod,
		'usu_login', usulogin,
        'fecha y hora', to_char(current_timestamp,'dd-mm-yyyy hh24:mi:ss'),
        'transaccion', upper(transaccion),
		'uni_descri', upper(unidescri), 
		'uni_simbolo', upper(unisimbolo), 
		'uni_estado', upper(uniestado)
    )||','
    WHERE uni_cod = unicod;
end--finalizar
$$
language plpgsql;


--sp_abm_horariosCom (HORARIOS COMIDA)
CREATE OR REPLACE FUNCTION sp_abm_horariosCom
(hrcomcod integer, 
hrcomdescri varchar, 
hrcomestado varchar, 
operacion integer,
usucod integer,
usulogin varchar,
transaccion varchar)
RETURNS void
AS $$
declare hrcomaudit text;
begin --iniciar
	--se designan validaciones
	if operacion in (1,2) then
		perform * from horarios_comida
		where hrcom_descri = upper(hrcomdescri) and hrcom_cod != hrcomcod;
		if found then
			raise exception '1';
		elseif operacion = 1 then --realizamos un insert
			INSERT INTO horarios_comida
			(hrcom_cod, 
			hrcom_descri, 
			hrcom_estado)
			VALUES(
			hrcomcod, 
			upper(hrcomdescri), 
			'ACTIVO');
			raise notice 'EL HORARIO PARA COMER FUE REGISTRADO CON EXITO';
		elseif operacion = 2 then -- realizamos un update 
			UPDATE horarios_comida
			SET hrcom_descri = upper(hrcomdescri), 
			hrcom_estado = 'ACTIVO'
			WHERE hrcom_cod = hrcomcod;
			raise notice 'EL HORARIO PARA COMER FUE MODIFICADO CON EXITO';
		end if;
	end if;
	if operacion = 3 then -- realizamos un update 
		update horarios_comida
		set hrcom_estado = 'INACTIVO'
		WHERE hrcom_cod = hrcomcod;
		raise notice 'EL HORARIO PARA COMER FUE BORRADO CON EXITO';
	end if;
	--se selecciona la ultima auditoria
	select coalesce(hrcom_audit,'') into hrcomaudit
	from horarios_comida
	where hrcom_cod = hrcomcod;

	--se actualiza la auditoria
	update horarios_comida
    set hrcom_audit = hrcomaudit||' '||json_build_object(
        'usu_cod', usucod,
		'usu_login', usulogin,
        'fecha y hora', to_char(current_timestamp,'dd-mm-yyyy hh24:mi:ss'),
        'transaccion', upper(transaccion),
		'hrcom_descri', upper(hrcomdescri), 
		'hrcom_estado', upper(hrcomestado)
    )||','
    WHERE hrcom_cod = hrcomcod;
end--finalizar
$$
language plpgsql;


-------------------------------MOVIMIENTOS-------------------------------

--sp_inscripciones_cab (INSCRIPCIONES CABECERA)
CREATE OR REPLACE FUNCTION sp_inscripciones_cab
(inscod integer,
insfecha date,
insestado varchar,
usucod integer,
succod integer,
empcod integer,
clicod integer,
operacion integer,
usulogin varchar,
sucdescri varchar,
emprazonsocial varchar,
pernrodoc varchar,
cliente varchar,
transaccion varchar)
RETURNS void
AS $$
declare insaudit text;
begin 
    if operacion = 1 then
		perform * from inscripciones_cab
		where cli_cod = clicod 
			and ins_cod != inscod
			and ins_estado != 'ANULADO';
		if found then
			raise exception '1';
	    elseif operacion = 1 then
        -- aqui hacemos un insert
	        INSERT INTO inscripciones_cab 
		        (ins_cod,
		        ins_fecha, 
		        ins_estado,
		        usu_cod, 
		        suc_cod,
		        emp_cod,
		        cli_cod)
	        VALUES
		        (inscod,
		        insfecha,
		      	'ACTIVO',
		        usucod,
		        succod,
		      	empcod,
		      	clicod);
	        raise notice 'LA INSCRIPCION FUE REGISTADA CON EXITO';
	    end if;
    end if;
    if operacion = 2 then
        -- aqui hacemos un update
		update inscripciones_cab 
			SET ins_estado = 'ANULADO',
			usu_cod = usucod
        WHERE ins_cod = inscod;
        raise notice 'LA INSCRIPCION FUE ANULADA';
    end if;
	--se selecciona la ultima auditoria
	select coalesce(ins_audit,'') into insaudit
	from inscripciones_cab
	where ins_cod = inscod;

	--se actualiza la auditoria
	update inscripciones_cab
    set ins_audit = insaudit||' '||json_build_object(
        'usu_cod', usucod,
		'usu_login', usulogin,
        'fecha y hora', to_char(current_timestamp,'dd/mm/yyyy hh24:mi:ss'),
        'transaccion', upper(transaccion),
        'ins_fecha', to_char(insfecha,'dd/mm/yyyy'),
        'cliente', upper(cliente),
        'nro_documento', pernrodoc,
        'emp_cod', empcod,
        'emp_razonsocial', upper(emprazonsocial),
        'suc_cod', succod,
		'suc_descri', upper(sucdescri), 
		'ins_estado', upper(insestado)
    )||','
    WHERE ins_cod = inscod;
end
$$
language plpgsql;

--sp_inscripciones_det (INSCRIPCIONES DETALLE)
CREATE OR REPLACE FUNCTION sp_inscripciones_det
(inscod integer, 
diacod integer, 
insdethorainicio time, 
insdethorafinal time, 
operacion integer)
RETURNS void
AS $$
begin 
	if operacion = 1 then
		perform * from inscripciones_det
		where dia_cod = diacod and ins_cod = inscod;
		if found then
			raise exception '1';
	    elseif operacion = 1 then
	        -- aqui hacemos un insert
	        INSERT INTO inscripciones_det 
		        (ins_cod,
		        dia_cod, 
		        insdet_horainicio, 
		        insdet_horafinal)
	        VALUES
				(inscod,
		        diacod,
		        insdethorainicio,
		        insdethorafinal);
	        raise notice 'EL DETALLE FUE REGISTADO CON EXITO';
	    end if;
	end if;
    if operacion = 2 then
    	-- aqui hacemos un delete
		delete from inscripciones_det 
		where 
		dia_cod = diacod
		and ins_cod = inscod;
		raise notice 'EL DETALLE FUE ELIMINADO CON EXITO';
	end if;
end
$$
language plpgsql;

--sp_presupuesto_prep_cab (PRESUPUESTO DE PREPARACION CABECERA)
CREATE OR REPLACE FUNCTION sp_presupuesto_prep_cab
(prprcod integer,
prprfecha date,
prprestado varchar,
inscod integer,
clicod integer,
usucod integer,
succod integer,
empcod integer,
prprfechavenci date,
operacion integer,
pernrodoc varchar,
cliente varchar,
sucdescri varchar,
emprazonsocial varchar,
usulogin varchar,
transaccion varchar)
RETURNS void
AS $$
declare prpraudit text;
begin 
    if operacion = 1 then
		if current_date > prprfechavenci then
			raise exception 'fecha';
		end if;
		perform * from presupuesto_prep_cab
		where cli_cod = clicod 
			and ins_cod = inscod 
			and prpr_estado not in ('ANULADO','CANCELADO')
			and prpr_cod != prprcod;
		if found then
			raise exception 'client';
	    elseif operacion = 1 then
        -- aqui hacemos un insert
	        INSERT INTO presupuesto_prep_cab 
		        (prpr_cod,
		        prpr_fecha, 
		        prpr_estado,
		        ins_cod,
		        cli_cod,
		        usu_cod,
		        suc_cod,
		        emp_cod,
				prpr_fechavenci)
	        VALUES
		        (prprcod,
		        prprfecha,
		      	'ACTIVO',
		      	inscod,
		      	clicod,
		    	usucod,
		        succod,
		      	empcod,
				prprfechavenci);
	    	raise notice 'EL PRESUPUESTO FUE REGISTADO CON EXITO';
	  	end if;
    end if;
    if operacion = 2 then
        -- aqui hacemos un update
		update presupuesto_prep_cab 
			SET prpr_estado = 'ANULADO',
			usu_cod = usucod
        WHERE prpr_cod = prprcod;
        raise notice 'EL PRESUPUESTO FUE ANULADO';
    end if;
	--se selecciona la ultima auditoria
	select coalesce(prpr_audit,'') into prpraudit
	from presupuesto_prep_cab
	where prpr_cod = prprcod;

	--se actualiza la auditoria
	update presupuesto_prep_cab
    set prpr_audit = prpraudit||' '||json_build_object(
        'usu_cod', usucod,
		'usu_login', usulogin,
        'fecha y hora', to_char(current_timestamp,'dd/mm/yyyy hh24:mi:ss'),
        'transaccion', upper(transaccion),
        'prpr_fecha', to_char(prprfecha,'dd/mm/yyyy'),
        'prpr_fechavenci', to_char(prprfechavenci,'dd/mm/yyyy'),
        'ins_cod', inscod,
        'cliente', upper(cliente),
        'nro_documento', pernrodoc,
        'emp_cod', empcod,
        'emp_razonsocial', upper(emprazonsocial),
        'suc_cod', succod,
		'suc_descri', upper(sucdescri), 
		'prpr_estado', upper(prprestado)
    )||','
    WHERE prpr_cod = prprcod;
end
$$
language plpgsql;

--sp_presupuesto_prep_det (PRESUPUESTO PREPARACIÓN DETALLE)
CREATE OR REPLACE FUNCTION sp_presupuesto_prep_det
(itmcod integer, 
tipitemcod integer, 
prprcod integer, 
prprdetcantidad numeric, 
prprdetprecio numeric, 
operacion integer)
RETURNS void
AS $$
begin 
	if operacion = 1 then
		perform * from presupuesto_prep_det
		where itm_cod = itmcod and prpr_cod = prprcod;
		if found then
			raise exception '1';
	    elseif operacion = 1 then
	        -- aqui hacemos un insert
	        INSERT INTO presupuesto_prep_det 
		        (itm_cod,
		        tipitem_cod, 
		        prpr_cod, 
		        prprdet_cantidad,
		        prprdet_precio)
	        VALUES
				(itmcod,
		        tipitemcod,
		        prprcod,
		        prprdetcantidad,
		        prprdetprecio);
	        raise notice 'EL DETALLE FUE REGISTADO CON EXITO';
	    end if;
	end if;
    if operacion = 2 then
    	-- aqui hacemos un delete
		delete from presupuesto_prep_det 
		where itm_cod = itmcod 
			and tipitem_cod = tipitemcod 
			and prpr_cod = prprcod;
		raise notice 'EL DETALLE FUE ELIMINADO CON EXITO';
	end if;
end
$$
language plpgsql;

--sp_mediciones_cab (MEDICIONES CABECERA)
CREATE OR REPLACE FUNCTION sp_mediciones_cab
(medcod integer,
medfecha date,
medestado varchar,
clicod integer, 
usucod integer, 
succod integer, 
empcod integer,  
prprcod integer,
operacion integer,
pernrodoc varchar,
cliente varchar,
usulogin varchar,
sucdescri varchar,
emprazonsocial varchar,
transaccion varchar)
RETURNS void
AS $$
declare medaudit text;
begin 
    if operacion = 1 then
		perform * from mediciones_cab
		where cli_cod = clicod 
			and med_cod != medcod 
			and med_estado != 'ANULADO';
		if found then
			raise exception '1';
	    elseif operacion = 1 then
        -- aqui hacemos un insert
	        INSERT INTO mediciones_cab 
		        (med_cod,
		        med_fecha,
		        med_estado,
		        cli_cod,
		        usu_cod, 
		        suc_cod,
		        emp_cod,
				prpr_cod)
	        VALUES
		        (medcod,
		        medfecha,
		      	'ACTIVO',
		      	clicod,
		        usucod,
		        succod,
		      	empcod,
				prprcod);
	        raise notice 'LA MEDICION FUE REGISTADA CON EXITO';
	    end if;
    end if;
    if operacion = 2 then
        -- aqui hacemos un update
		update mediciones_cab 
			SET med_estado = 'ANULADO',
			usu_cod = usucod
        WHERE med_cod = medcod;
        raise notice 'LA MEDICION FUE ANULADA';
    end if;
	--se selecciona la ultima auditoria
	select coalesce(med_audit,'') into medaudit
	from mediciones_cab
	where med_cod = medcod;

	--se actualiza la auditoria
	update mediciones_cab
    set med_audit = medaudit||' '||json_build_object(
        'usu_cod', usucod,
		'usu_login', usulogin,
        'fecha y hora', to_char(current_timestamp,'dd/mm/yyyy hh24:mi:ss'),
        'transaccion', upper(transaccion),
        'med_fecha', to_char(medfecha,'dd/mm/yyyy'),
		'prpr_cod', prprcod,
        'cliente', upper(cliente),
        'nro_documento', pernrodoc,
        'emp_cod', empcod,
        'emp_razonsocial', upper(emprazonsocial),
        'suc_cod', succod,
		'suc_descri', upper(sucdescri), 
		'med_estado', upper(medestado)
    )||','
    WHERE med_cod = medcod;
end
$$
LANGUAGE plpgsql;

--sp_mediciones_det (MEDICIONES DETALLE)
CREATE OR REPLACE FUNCTION sp_mediciones_det
(medcod integer, 
paramcod integer, 
meddetcantidad numeric, 
operacion integer)
RETURNS void
AS $$
begin 
	if operacion = 1 then
		perform * from mediciones_det
		where param_cod = paramcod and med_cod = medcod;
		if found then
			raise exception '1';
	    elseif operacion = 1 then
	        -- aqui hacemos un insert
	        INSERT INTO mediciones_det 
		        (med_cod,
		        param_cod,
		        meddet_cantidad)
	        VALUES
				(medcod,
		        paramcod,
		        meddetcantidad);
	        raise notice 'EL DETALLE FUE REGISTADO CON EXITO';
	    end if;
	end if;
    if operacion = 2 then
    	-- aqui hacemos un delete
		delete from mediciones_det 
		where param_cod = paramcod
			and med_cod = medcod;
		raise notice 'EL DETALLE FUE ELIMINADO CON EXITO';
	end if;
end
$$
LANGUAGE plpgsql;

--sp_plan_alimenticio_cab (PLAN ALIMENTICIO CABECERA)
create or replace function sp_plan_alimenticio_cab
(alimcod integer,
alimestado varchar,
tiplancod integer,
clicod integer,
funcod integer,
usucod integer,
succod integer,
empcod integer,
alimfecha date,
prprcod integer,
operacion integer,
tiplandescri varchar,
pernrodoc varchar,
cliente varchar,
funcionario varchar,
usulogin varchar,
sucdescri varchar,
emprazonsocial varchar,
transaccion varchar)
RETURNS void
AS $$
declare alimaudit text;
begin 
    if operacion = 1 then
		perform * from plan_alimenticio_cab
		where cli_cod = clicod 
			and alim_cod != alimcod
			and alim_estado != 'ANULADO';
		if found then
			raise exception '1';
	    elseif operacion = 1 then
        -- aqui hacemos un insert
	        INSERT INTO plan_alimenticio_cab 
		        (alim_cod, 
		        alim_estado,
		        tiplan_cod,
		        cli_cod,
		        fun_cod,
		        usu_cod, 
		        suc_cod,
		        emp_cod,
				alim_fecha,
				prpr_cod)
	        VALUES
		        (alimcod,
		      	'ACTIVO',
		      	tiplancod,
		      	clicod,
		      	funcod,
		        usucod,
		        succod,
		      	empcod,
				alimfecha,
				prprcod);
	        raise notice 'EL PLAN FUE REGISTADO CON EXITO';
	    end if;
    end if;
    if operacion = 2 then
        -- aqui hacemos un update
		update plan_alimenticio_cab 
			SET alim_estado = 'ANULADO',
			usu_cod = usucod
        WHERE alim_cod = alimcod;
        raise notice 'EL PLAN FUE ANULADO';
    end if;
	--se selecciona la ultima auditoria
	select coalesce(alim_audit,'') into alimaudit
	from plan_alimenticio_cab
	where alim_cod = alimcod;

	--se actualiza la auditoria
	update plan_alimenticio_cab
    set alim_audit = alimaudit||' '||json_build_object(
        'usu_cod', usucod,
		'usu_login', usulogin,
        'fecha y hora', to_char(current_timestamp,'dd/mm/yyyy hh24:mi:ss'),
        'transaccion', upper(transaccion),
        'alim_fecha', to_char(alimfecha,'dd/mm/yyyy'),
        'tiplan_cod', tiplancod,
		'tiplan_descri', upper(tiplandescri),
		'prpr_cod', prprcod,
        'cliente', upper(cliente),
        'nro_documento', pernrodoc,
		'fun_cod', funcod,
		'nutricionista', upper (funcionario),
        'emp_cod', empcod,
        'emp_razonsocial', upper(emprazonsocial),
        'suc_cod', succod,
		'suc_descri', upper(sucdescri), 
		'alim_estado', upper(alimestado)
    )||','
    WHERE alim_cod = alimcod;
end
$$
language plpgsql;

--sp_plan_alimenticio_det (PLAN ALIMENTICIO DETALLE)
create or replace function sp_plan_alimenticio_det
(alimcod integer, 
comicod integer, 
alimdetproteina numeric, 
alimdetcalorias numeric, 
alimdetcarbohidratos numeric, 
diacod integer, 
hrcomcod integer, 
operacion integer)
RETURNS void
AS $$
begin 
	if operacion = 1 then
		perform * from plan_alimenticio_det
		where dia_cod = diacod 
			and hrcom_cod = hrcomcod 
			and alim_cod = alimcod;
		if found then
			raise exception '1';
	    elseif operacion = 1 then
	        -- aqui hacemos un insert
	        INSERT INTO plan_alimenticio_det 
		        (alim_cod,
		        comi_cod, 
		        alimdet_proteina, 
		        alimdet_calorias,
		        alimdet_carbohidratos,
		        dia_cod,
		        hrcom_cod)
	        VALUES(
		        alimcod,
		        comicod,
		        alimdetproteina,
		        alimdetcalorias,
		        alimdetcarbohidratos,
		        diacod,
		        hrcomcod);
	        raise notice 'EL DETALLE FUE REGISTADO CON EXITO';
	    end if;
	end if;
    if operacion = 2 then
    	-- aqui hacemos un delete
		delete from plan_alimenticio_det 
		where 
		comi_cod = comicod
		and dia_cod = diacod
		and hrcom_cod = hrcomcod
		and alim_cod = alimcod;
		raise notice 'EL DETALLE FUE ELIMINADO CON EXITO';
	end if;
end
$$
LANGUAGE plpgsql;

--sp_rutinas_cab (RUTINAS CABECERA)
create or replace function sp_rutinas_cab
(rutcod integer, 
rutestado varchar, 
tiprutcod integer, 
clicod integer, 
funcod integer, 
usucod integer, 
succod integer, 
empcod integer,
rutfecha date,
prprcod integer,
operacion integer,
tiprutdescri varchar,
pernrodoc varchar,
cliente varchar,
funcionario varchar,
usulogin varchar,
sucdescri varchar,
emprazonsocial varchar,
transaccion varchar)
RETURNS void
AS $$
declare rutaudit text;
begin 
    if operacion = 1 then
		perform * from rutinas_cab
		where cli_cod = clicod 
			and tiprut_cod = tiprutcod 	
			and rut_cod != rutcod
			and rut_estado != 'ANULADO';
		if found then
			raise exception '1';
	    elseif operacion = 1 then
        -- aqui hacemos un insert
	        INSERT INTO rutinas_cab 
		        (rut_cod, 
		        rut_estado,
		        tiprut_cod,
		        cli_cod,
		        fun_cod,
		        usu_cod, 
		        suc_cod,
		        emp_cod,
				rut_fecha,
				prpr_cod)
	        VALUES
		        (rutcod,
		      	'ACTIVO',
		      	tiprutcod,
		      	clicod,
		      	funcod,
		        usucod,
		        succod,
		      	empcod,
				rutfecha,
				prprcod);
	        raise notice 'LA RUTINA FUE REGISTADA CON EXITO';
	    end if;
    end if;
    if operacion = 2 then
        -- aqui hacemos un update
		update rutinas_cab 
			SET rut_estado = 'ANULADO',
			usu_cod = usucod
        WHERE rut_cod = rutcod;
        raise notice 'LA RUTINA FUE ANULADA';
    end if;
	--se selecciona la ultima auditoria
	select coalesce(rut_audit,'') into rutaudit
	from rutinas_cab
	where rut_cod = rutcod;

	--se actualiza la auditoria
	update rutinas_cab
    set rut_audit = rutaudit||' '||json_build_object(
        'usu_cod', usucod,
		'usu_login', usulogin,
        'fecha y hora', to_char(current_timestamp,'dd/mm/yyyy hh24:mi:ss'),
        'transaccion', upper(transaccion),
        'rut_fecha', to_char(rutfecha,'dd/mm/yyyy'),
        'tiprut_cod', tiprutcod,
		'tiprut_descri', upper(tiprutdescri),
		'prpr_cod', prprcod,
        'cliente', upper(cliente),
        'nro_documento', pernrodoc,
		'fun_cod', funcod,
		'entrenador', upper (funcionario),
        'emp_cod', empcod,
        'emp_razonsocial', upper(emprazonsocial),
        'suc_cod', succod,
		'suc_descri', upper(sucdescri), 
		'rut_estado', upper(rutestado)
    )||','
    WHERE rut_cod = rutcod;
end
$$
LANGUAGE plpgsql;

--sp_rutinas_det (RUTINAS DETALLES)
create or replace function sp_rutinas_det
(rutcod integer, 
ejercod integer, 
rutdetseries integer, 
rutdetrepeticiones integer, 
diacod integer, 
equicod integer, 
tipequicod integer, 
operacion integer)
 RETURNS void
AS $$
begin 
	if operacion = 1 then
		perform * from rutinas_det
		where ejer_cod = ejercod 
			and dia_cod = diacod
			and rut_cod = rutcod;
		if found then
			raise exception '1';
	    elseif operacion = 1 then
	        -- aqui hacemos un insert
	        INSERT INTO rutinas_det 
		        (rut_cod,
		        ejer_cod, 
		        rutdet_series, 
		        rutdet_repeticiones,
		        dia_cod,
		        equi_cod,
		        tipequi_cod)
	        VALUES
				(rutcod,
		        ejercod,
		        rutdetseries,
		        rutdetrepeticiones,
		        diacod,
		        equicod,
		        tipequicod);
	        raise notice 'EL DETALLE FUE REGISTADO CON EXITO';
	    end if;
	end if;
    if operacion = 2 then
    	-- aqui hacemos un delete
		delete from rutinas_det 
		where ejer_cod = ejercod
			and dia_cod = diacod
			and equi_cod = equicod
			and tipequi_cod = tipequicod
			and rut_cod = rutcod;
		raise notice 'EL DETALLE FUE ELIMINADO CON EXITO';
	end if;
end
$$
LANGUAGE plpgsql;

--sp_evolucion_cab(EVOLUCION CABECERA)
CREATE OR REPLACE FUNCTION sp_evolucion_cab
(evocod integer,
evofecha date,
evoestado varchar,
clicod integer,
usucod integer,
succod integer,
empcod integer,
medcod integer,
operacion integer,
pernrodoc varchar,
cliente varchar,
usulogin varchar,
sucdescri varchar,
emprazonsocial varchar,
transaccion varchar)
RETURNS void
AS $$
declare 
	evoaudit text;
	cu_evolucion_det cursor is
		select 
			evodet_registro_ant,
			param_cod
		from evolucion_det 
		where evo_cod = evocod;
begin 
    if operacion = 1 then
		perform * from evolucion_cab
		where cli_cod = clicod 
			and evo_cod != evocod
			and evo_estado != 'ANULADO';
		if found then
			raise exception '1';
	    elseif operacion = 1 then
        -- aqui hacemos un insert
	        INSERT INTO evolucion_cab 
		        (evo_cod,
		        evo_fecha, 
		        evo_estado,
		        cli_cod,
		        usu_cod, 
		        suc_cod,
		        emp_cod,
				med_cod)
	        VALUES
		        (evocod,
		        evofecha,
		      	'ACTIVO',
		      	clicod,
		        usucod,
		        succod,
		      	empcod,
				medcod);
	        raise notice 'LA EVOLUCION FUE REGISTADA CON EXITO';
	    end if;
    end if;
    if operacion = 2 then
    -- aqui hacemos un update
		update evolucion_cab 
			SET evo_estado = 'ANULADO',
			usu_cod = usucod
        WHERE evo_cod = evocod;
	--se actualiza mediciones detalle
		for det in cu_evolucion_det loop
			update mediciones_det 
				set meddet_cantidad = det.evodet_registro_ant
			where med_cod = medcod
				and param_cod = det.param_cod;
		end loop;
        raise notice 'LA EVOLUCION FUE ANULADA';
    end if;

	--se selecciona la ultima auditoria
	select coalesce(evo_audit,'') into evoaudit
	from evolucion_cab
	where evo_cod = evocod;

	--se actualiza la auditoria
	update evolucion_cab
	    set evo_audit = evoaudit||' '||json_build_object(
	        'usu_cod', usucod,
			'usu_login', usulogin,
	        'fecha y hora', to_char(current_timestamp,'dd/mm/yyyy hh24:mi:ss'),
	        'transaccion', upper(transaccion),
	        'evo_fecha', to_char(evofecha,'dd/mm/yyyy'),
        	'med_cod', medcod,
	        'cliente', upper(cliente),
	        'nro_documento', pernrodoc,
	        'emp_cod', empcod,
	        'emp_razonsocial', upper(emprazonsocial),
	        'suc_cod', succod,
			'suc_descri', upper(sucdescri), 
			'evo_estado', upper(evoestado)
	    )||','
    WHERE evo_cod = evocod;
end
$$
language plpgsql;

--sp_evolucion_det (MEDICIONES DETALLE)
CREATE OR REPLACE FUNCTION sp_evolucion_det
(evocod integer,
paramcod integer, 
evodetregistroant numeric, 
evodetregistroact numeric, 
operacion integer,
medcod integer)
RETURNS void
AS $$
begin 
	if operacion = 1 then
		perform * from evolucion_det
		where param_cod = paramcod 
			and evo_cod = evocod;
		if found then
			raise exception '1';
	    elseif operacion = 1 then
	     --aqui hacemos un insert
	        INSERT INTO evolucion_det 
		        (evo_cod,
		        param_cod,
		        evodet_registro_ant,
				evodet_registro_act)
	        VALUES
				(evocod,
		        paramcod,
		        evodetregistroant,
				evodetregistroact);
		 --se actualiza mediciones detalle
			update mediciones_det 
				set meddet_cantidad = evodetregistroact
			where med_cod = medcod
				and param_cod = paramcod;
	        raise notice 'EL DETALLE FUE REGISTADO CON EXITO';
	    end if;
	end if;
	if operacion = 2 then
		perform * from evolucion_det
		where param_cod = paramcod 
			and evo_cod = evocod;
		if found then
	 	 --se realiza un update
			update evolucion_det 
				set param_cod = paramcod,
				evodet_registro_ant = evodetregistroant,
				evodet_registro_act = evodetregistroact
			where evo_cod = evocod
				and param_cod = paramcod;
		 --se actualiza mediciones detalle
			update mediciones_det 
				set meddet_cantidad = evodetregistroact
			where med_cod = medcod
				and param_cod = paramcod;
	    	raise notice 'EL DETALLE FUE MODIFICADO CON EXITO';
		else
			raise exception '2';
		end if;
	end if;
    if operacion = 3 then
     --aqui hacemos un delete
		delete from evolucion_det 
		where param_cod = paramcod
			and evo_cod = evocod;
	 --se actualiza mediciones detalle
		update mediciones_det 
			set meddet_cantidad = evodetregistroant
		where med_cod = medcod
			and param_cod = paramcod;
		raise notice 'EL DETALLE FUE ELIMINADO CON EXITO';
	end if;
end
$$
LANGUAGE plpgsql;

--sp_asistencias (ASISTENCIAS)
create or replace function sp_asistencias
(asiscod integer, 
asisfecha date,
asishoraentrada time,
asishorasalida time,
clicod integer, 
usucod integer, 
succod integer, 
empcod integer,
operacion integer)
 RETURNS void
AS $$
begin 
	if operacion = 1 then
		perform * from asistencias
		where asis_fecha = asisfecha 
			and cli_cod = clicod
			and asis_cod != asiscod;
		if found then
			raise exception '1';
	    elseif operacion = 1 then
	        -- aqui hacemos un insert
	        INSERT INTO asistencias 
		        (asis_cod,
		        asis_fecha, 
		        asis_horaentrada, 
		        asis_horasalida,
		        cli_cod,
		        usu_cod,
		        suc_cod,
				emp_cod)
	        VALUES
		        (asiscod,
		        asisfecha, 
		        asishoraentrada, 
		        asishorasalida,
		        clicod,
		        usucod,
		        succod,
				empcod);
	        raise notice 'LA ASISTENCIA FUE REGISTADA CON EXITO';
	    end if;
	end if;
    if operacion = 2 then
    	-- aqui hacemos un delete
		delete from asistencias 
		where asis_cod = asiscod;
		raise notice 'ELA ASISTENCIA FUE ELIMINADA CON EXITO';
	end if;
end
$$
LANGUAGE plpgsql;

--sp_salidas(SALIDAS)
CREATE OR REPLACE FUNCTION sp_salidas
(salcod integer,
salfecha date,
salmotivo varchar,
salestado varchar,
clicod integer,
inscod integer,
usucod integer,
succod integer,
empcod integer,
operacion integer,
pernrodoc varchar,
cliente varchar,
usulogin varchar,
sucdescri varchar,
emprazonsocial varchar,
transaccion varchar)
RETURNS void
AS $$
declare 
	salaudit text;
	cu_inscripcion cursor is
		select 
			ic.*,
			p2.per_nrodoc,
			p2.per_nombres||' '||p2.per_apellidos as cliente
		from inscripciones_cab ic
			join clientes c on c.cli_cod = ic.cli_cod
				join personas p2 on p2.per_cod = c.per_cod 
		where ins_cod = inscod;
begin 
    if operacion = 1 then
		perform * from salidas
		where ins_cod = inscod 
			and sal_cod != salcod
			and sal_estado != 'ANULADO';
		if found then
			raise exception '1';
	    elseif operacion = 1 then
        -- aqui hacemos un insert
	        INSERT INTO salidas 
		        (sal_cod,
		        sal_fecha, 
				sal_motivo,
		        sal_estado,
		        cli_cod,
				ins_cod,
		        usu_cod, 
		        suc_cod,
		        emp_cod)
	        VALUES
		        (salcod,
		        salfecha,
				upper(salmotivo),
		      	'ACTIVO',
		      	clicod,
				inscod,
		        usucod,
		        succod,
		      	empcod);
		--se actualiza la inscripcion
			update inscripciones_cab
				set ins_estado = 'ANULADO',
				usu_cod = usucod
			where ins_cod = inscod;
	        raise notice 'LA SALIDA FUE REGISTADA CON EXITO';
	    end if;
    end if;
    if operacion = 2 then
        -- aqui hacemos un update
		update salidas 
			SET sal_estado = 'ANULADO',
			usu_cod = usucod
        WHERE sal_cod = salcod;
		--se actualiza la inscripcion
		update inscripciones_cab
			set ins_estado = 'ACTIVO',
			usu_cod = usucod
		where ins_cod = inscod;
        raise notice 'LA SALIDA FUE ANULADA';
    end if;

	--se selecciona la ultima auditoria
	select coalesce(sal_audit,'') into salaudit
	from salidas
	where sal_cod = salcod;

	--se actualiza la auditoria
	update salidas
    set sal_audit = salaudit||' '||json_build_object(
        'usu_cod', usucod,
		'usu_login', usulogin,
        'fecha y hora', to_char(current_timestamp,'dd/mm/yyyy hh24:mi:ss'),
        'transaccion', upper(transaccion),
        'sal_fecha', to_char(salfecha,'dd/mm/yyyy'),
		'sal_motivo', upper(salmotivo),
        'ins_cod', inscod,
        'cliente', upper(cliente),
        'nro_documento', pernrodoc,
        'emp_cod', empcod,
        'emp_razonsocial', upper(emprazonsocial),
        'suc_cod', succod,
		'suc_descri', upper(sucdescri), 
		'sal_estado', upper(salestado)
    )||','
    WHERE sal_cod = salcod;

	--se abre el cursor de inscripciones
	for ins in cu_inscripcion loop
		--se actualiza la auditoria de inscripcion del cliente que esta saliendo
		update inscripciones_cab
	    set ins_audit = ins.ins_audit||' '||json_build_object(
	        'usu_cod', usucod,
			'usu_login', usulogin,
	        'fecha y hora', to_char(current_timestamp,'dd/mm/yyyy hh24:mi:ss'),
	        'transaccion', 'MODIFICACION (SALIDA N° '||salcod||')',
	        'ins_fecha', to_char(ins.ins_fecha,'dd/mm/yyyy'),
	        'cliente', upper(ins.cliente),
	        'nro_documento', ins.per_nrodoc,
			'entrenador', upper (ins.funcionario),
	        'emp_cod', empcod,
	        'emp_razonsocial', upper(emprazonsocial),
	        'suc_cod', succod,
			'suc_descri', upper(sucdescri), 
			'ins_estado', upper(ins.ins_estado)
	    )||','
	    WHERE ins_cod = inscod;
	end loop;
end
$$
language plpgsql;

-------------------------------VISTAS-------------------------------

--v_inscripciones_cab (INSCRIPCIONES CABECERA)
create or replace view v_inscripciones_cab as
select 
ic.ins_cod,
to_char(ic.ins_fecha, 'dd/mm/yyyy') as ins_fecha,
ic.usu_cod,
u.usu_login,
ic.suc_cod,
s.suc_descri,
ic.emp_cod,
e.emp_razonsocial,
ic.cli_cod,
p2.per_nrodoc,
p2.per_telefono,
p2.per_nombres||' '||p2.per_apellidos as cliente,
ic.ins_estado 
from inscripciones_cab ic
join clientes c on c.cli_cod = ic.cli_cod
	join personas p2 on p2.per_cod = c.per_cod 
join sucursales s on s.suc_cod = ic.suc_cod and s.emp_cod = ic.emp_cod
	join empresa e on e.emp_cod = s.emp_cod
join usuarios u on u.usu_cod = ic.usu_cod
where ic.ins_estado != 'ANULADO'
order by ic.ins_cod;

--v_inscripciones_det (INSCRIPCIONES DETALLE)
create or replace view v_inscripciones_det as
select 
id.ins_cod,
id.dia_cod,
d.dia_descri,
id.insdet_horainicio,
id.insdet_horafinal
from inscripciones_det id
join dias d on d.dia_cod = id.dia_cod 
order by id.ins_cod;

--v_presupuesto_prep_cab (PRESUPUESTO PREPARACIÓN CABECERA)
create or replace view v_presupuesto_prep_cab as
select 
ppc.prpr_cod,
to_char(ppc.prpr_fecha, 'dd/mm/yyyy') as prpr_fecha,
ppc.suc_cod,
s.suc_descri,
ppc.emp_cod,
e.emp_razonsocial,
ppc.usu_cod,
u.usu_login,
ppc.ins_cod,
ppc.cli_cod,
p.per_nrodoc,
p.per_email,
p.per_nombres||' '||p.per_apellidos AS cliente,
ppc.prpr_fechavenci,
to_char(ppc.prpr_fechavenci, 'dd/mm/yyyy') as prpr_fechavenci2,
ppc.prpr_estado 
from presupuesto_prep_cab ppc
join clientes c on c.cli_cod = ppc.cli_cod
	join personas p on p.per_cod = c.per_cod
join inscripciones_cab ic on ic.ins_cod = ppc.ins_cod	
join sucursales s on s.suc_cod = ppc.suc_cod and s.emp_cod = ppc.emp_cod
	join empresa e on e.emp_cod = s.emp_cod
join usuarios u on u.usu_cod = ppc.usu_cod
where ppc.prpr_estado != 'ANULADO'
order by ppc.prpr_cod;

--v_presupuesto_prep_det (PRESUPUESTP PREPARACIÓN DETALLE)
create or replace view v_presupuesto_prep_det as
select 
ppd.prpr_cod,
ppd.itm_cod,
ppd.tipitem_cod,
i.tipimp_cod,
i.itm_descri,
ppd.prprdet_precio,
ppd.prprdet_cantidad,
(case i.tipimp_cod when 1 then ppd.prprdet_cantidad * ppd.prprdet_precio else 0 end) as exenta,
(case i.tipimp_cod when 2 then ppd.prprdet_cantidad * ppd.prprdet_precio else 0 end) as iva5,
(case i.tipimp_cod when 3 then ppd.prprdet_cantidad * ppd.prprdet_precio else 0 end) as iva10
from presupuesto_prep_det ppd 
join items i on i.itm_cod = ppd.itm_cod and i.tipitem_cod = ppd.tipitem_cod
	join tipo_item ti on ti.tipitem_cod = i.tipitem_cod 
order by ppd.prpr_cod;

--v_mediciones_cab (MEDICIONES CABECERA)
create or replace view v_mediciones_cab as 
select 
mc.med_cod,
mc.emp_cod,
e.emp_razonsocial,
mc.suc_cod,
s.suc_descri,
mc.usu_cod,
u.usu_login,
to_char(mc.med_fecha, 'dd/mm/yyyy') as med_fecha,
mc.cli_cod,
p2.per_nrodoc,
p2.per_nombres||' '||p2.per_apellidos AS cliente,
mc.prpr_cod,
ppc.ins_cod,
mc.med_estado 
from mediciones_cab mc
	join clientes c on c.cli_cod = mc.cli_cod
		join personas p2 on p2.per_cod = c.per_cod
	join presupuesto_prep_cab ppc on ppc.prpr_cod = mc.prpr_cod 
		join inscripciones_cab ic on ic.ins_cod = ppc.ins_cod 
	join sucursales s on s.suc_cod = mc.suc_cod and s.emp_cod = mc.emp_cod
		join empresa e on e.emp_cod = s.emp_cod
	join usuarios u on u.usu_cod = mc.usu_cod
where mc.med_estado != 'ANULADO'
order by  mc.med_cod;

--v_mediciones_det (MEDICIONES DETALLE)
CREATE OR REPLACE VIEW v_mediciones_det AS 
select 
md.med_cod,
md.param_cod,
pm.param_descri,
um.uni_simbolo,
um.uni_descri||' ('||um.uni_simbolo||')' as uni_descri,
md.meddet_cantidad 
from mediciones_det md
join parametros_medicion pm on pm.param_cod = md.param_cod 
	join unidad_medida um on um.uni_cod = pm.uni_cod
order by md.med_cod;

--v_plan_alimenticio_cab (PLANES ALIMENTICIOS CABECERA)
create or replace view v_plan_alimenticio_cab as 
select 
pac.alim_cod,
pac.emp_cod,
e.emp_razonsocial,
pac.suc_cod,
s.suc_descri,
pac.usu_cod,
u.usu_login,
to_char(pac.alim_fecha, 'dd/mm/yyyy') as alim_fecha,
tpa.tiplan_descri,
pac.cli_cod,
p2.per_nrodoc,
p2.per_nombres||' '||p2.per_apellidos as cliente,
pac.prpr_cod,
ppc.ins_cod,
pac.fun_cod,
p.per_nombres||' '||p.per_apellidos as funcionario,
pac.alim_estado,
p2.per_email
from plan_alimenticio_cab pac
	join funcionarios f on f.fun_cod = pac.fun_cod
	    join personas p on p.per_cod = f.per_cod
	join clientes c on c.cli_cod = pac.cli_cod
		join personas p2 on p2.per_cod = c.per_cod
	join presupuesto_prep_cab ppc on ppc.prpr_cod = pac.prpr_cod 
		join inscripciones_cab ic on ic.ins_cod = ppc.ins_cod 
	join sucursales s on s.suc_cod = pac.suc_cod and s.emp_cod = pac.emp_cod
		join empresa e on e.emp_cod = s.emp_cod
	join usuarios u on u.usu_cod = pac.usu_cod
	join tipo_plan_alimenticio tpa on tpa.tiplan_cod = pac.tiplan_cod 
where pac.alim_estado != 'ANULADO'
order by  pac.alim_cod;

-- v_plan_alimenticio_det (INSCRIPCIONES DETALLE)
create or replace view v_plan_alimenticio_det as 
select 
plad.alim_cod,
plad.comi_cod,
c.comi_descri,
plad.dia_cod,
d.dia_descri,
plad.hrcom_cod,
hc.hrcom_descri,
plad.alimdet_proteina,
plad.alimdet_calorias,
plad.alimdet_carbohidratos 
from plan_alimenticio_det plad
join comidas c on c.comi_cod = plad.comi_cod
join dias d on d.dia_cod = plad.dia_cod
join horarios_comida hc on hc.hrcom_cod = plad.hrcom_cod
order by plad.alim_cod;


--v_rutinas_cab (RUTINAS CABECERA)
create or replace view v_rutinas_cab as
select 
rc.rut_cod,
rc.emp_cod,
e.emp_razonsocial,
rc.suc_cod,
s.suc_descri,
rc.usu_cod,
u.usu_login,
to_char(rc.rut_fecha, 'dd/mm/yyyy') as rut_fecha,
rc.tiprut_cod,
tpa.tiprut_descri,
rc.cli_cod,
p2.per_nrodoc,
p2.per_nombres||' '||p2.per_apellidos as cliente,
rc.prpr_cod,
ppc.ins_cod,
rc.fun_cod,
p.per_nombres||' '||p.per_apellidos as funcionario,
rc.rut_estado,
p2.per_email
from rutinas_cab rc
	join funcionarios f on f.fun_cod = rc.fun_cod
	    join personas p on p.per_cod = f.per_cod
	join clientes c on c.cli_cod = rc.cli_cod
		join personas p2 on p2.per_cod = c.per_cod
	join presupuesto_prep_cab ppc on ppc.prpr_cod = rc.prpr_cod 
		join inscripciones_cab ic on ic.ins_cod = ppc.ins_cod 
	join sucursales s on s.suc_cod = rc.suc_cod and s.emp_cod = rc.emp_cod
		join empresa e on e.emp_cod = s.emp_cod
	join usuarios u on u.usu_cod = rc.usu_cod
	join tipo_rutinas tpa on tpa.tiprut_cod = rc.tiprut_cod 
where rc.rut_estado != 'ANULADO'
order by  rc.rut_cod;

--v_rutinas_det (RUTINAS DETALLE)
create or replace view v_rutinas_det as
select 
rd.rut_cod,
rd.ejer_cod ,
e.ejer_descri,
rd.dia_cod,
d.dia_descri,
rd.equi_cod,
rd.tipequi_cod,
eq.equi_descri,
rd.rutdet_series,
rd.rutdet_repeticiones
from rutinas_det rd
join ejercicios e on e.ejer_cod = rd.ejer_cod
join dias d on d.dia_cod = rd.dia_cod
join equipos eq on eq.equi_cod = rd.equi_cod and eq.tipequi_cod = rd.tipequi_cod 
	join tipo_equipos te on te.tipequi_cod = eq.tipequi_cod 
order by rd.rut_cod;

--v_evolucion_cab (EVOLUCION CABECERA)
create or replace view v_evolucion_cab as
select 
ec.evo_cod,
to_char(ec.evo_fecha, 'dd/mm/yyyy') as evo_fecha,
ec.usu_cod,
u.usu_login,
ec.suc_cod,
s.suc_descri,
ec.emp_cod,
e.emp_razonsocial,
ec.cli_cod,
p2.per_nrodoc,
p2.per_telefono,
p2.per_nombres||' '||p2.per_apellidos as cliente,
ec.med_cod,
ec.evo_estado 
from evolucion_cab ec
join clientes c on c.cli_cod = ec.cli_cod
	join personas p2 on p2.per_cod = c.per_cod 
join sucursales s on s.suc_cod = ec.suc_cod and s.emp_cod = ec.emp_cod
	join empresa e on e.emp_cod = s.emp_cod
join usuarios u on u.usu_cod = ec.usu_cod
where ec.evo_estado != 'ANULADO'
order by ec.evo_cod;

--v_evolucion_det (EVOLUCION DETALLE)
create or replace view v_evolucion_det as
select 
ed.evo_cod,
ed.param_cod ,
pm.param_descri,
pm.param_formula,
pm.uni_cod,
um.uni_simbolo,
ed.evodet_registro_ant,
ed.evodet_registro_act
from evolucion_det ed
	join parametros_medicion pm on pm.param_cod = ed.param_cod
		join unidad_medida um on um.uni_cod = pm.uni_cod 
order by ed.evo_cod;

--v_asistencias (ASISTENCIAS)
create or replace view v_asistencias as
select 
a.asis_cod,
a.emp_cod,
e.emp_razonsocial,
a.suc_cod,
s.suc_descri,
a.usu_cod,
u.usu_login,
to_char(a.asis_fecha, 'dd/mm/yyyy') as asis_fecha,
a.cli_cod,
p2.per_nrodoc,
p2.per_nombres||' '||p2.per_apellidos as cliente,
a.asis_horaentrada,
a.asis_horasalida
from asistencias a 
	join clientes c on c.cli_cod = a.cli_cod
		join personas p2 on p2.per_cod = c.per_cod 
	join sucursales s on s.suc_cod = a.suc_cod and s.emp_cod = a.emp_cod
		join empresa e on e.emp_cod = s.emp_cod
	join usuarios u on u.usu_cod = a.usu_cod
order by a.asis_cod;

--v_salidas (SALIDAS)
create or replace view v_salidas as
select 
sa.sal_cod,
to_char(sa.sal_fecha, 'dd/mm/yyyy') as sal_fecha,
sa.usu_cod,
u.usu_login,
sa.suc_cod,
s.suc_descri,
sa.emp_cod,
e.emp_razonsocial,
sa.cli_cod,
p2.per_nrodoc,
p2.per_nombres||' '||p2.per_apellidos as cliente,
sa.ins_cod,
sa.sal_motivo,
sa.sal_estado 
from salidas sa
join clientes c on c.cli_cod = sa.cli_cod
	join personas p2 on p2.per_cod = c.per_cod 
join sucursales s on s.suc_cod = sa.suc_cod and s.emp_cod = sa.emp_cod
	join empresa e on e.emp_cod = s.emp_cod
join usuarios u on u.usu_cod = sa.usu_cod
where sa.sal_estado != 'ANULADO'
order by sa.sal_cod;

-----------------------------------------------------------TRIGGERS----------------------------------------------------------

--tg_inscripciones_det_auditoria (Auditoria de inscripciones detalle)
create or replace function sp_inscripciones_det_auditoria() 
returns trigger
as $$
	-- Se declaran los codigos y nombres de usuario para la tabla de auditoria
	declare
		usu_cod_old integer := (select ic.usu_cod from inscripciones_cab ic where ic.ins_cod = old.ins_cod);
		usu_login_old varchar := (select u.usu_login from usuarios u where u.usu_cod = usu_cod_old);
		usu_cod_new integer := (select ic.usu_cod from inscripciones_cab ic where ic.ins_cod = new.ins_cod);
		usu_login_new varchar := (select u.usu_login from usuarios u where u.usu_cod = usu_cod_new);
	begin
		-- Si la operacion es eliminar el registro
	    if (TG_OP = 'DELETE') then
	        insert into inscripciones_det_auditoria (
				indeaudi_operacion,
				usu_cod,
				usu_login,
				ins_cod,
				dia_cod,
				insdet_horainicio, 
				insdet_horafinal)
	        values (
				TG_OP, 
				usu_cod_old, 
				usu_login_old,
				old.ins_cod,
				old.dia_cod,
				old.insdet_horainicio,
				old.insdet_horafinal);
		-- Si la operacion es insertar un registro
	    elseif (TG_OP = 'INSERT') then
	        insert into inscripciones_det_auditoria (
				indeaudi_operacion,
				usu_cod,
				usu_login,
				ins_cod,
				dia_cod,
				insdet_horainicio, 
				insdet_horafinal)
	        values (
				TG_OP, 
				usu_cod_new, 
				usu_login_new,
				new.ins_cod,
				new.dia_cod,
				new.insdet_horainicio,
				new.insdet_horafinal);
	    end if;
		-- Se retorna null para cerrar la funcion
	    return null; 
	end; 
$$
language plpgsql;

create trigger tg_inscripciones_det_auditoria
after insert or delete on inscripciones_det
for each row execute function sp_inscripciones_det_auditoria();

--tg_presupuesto_prep_det_auditoria (AUDITORIA DE PRESUPUESTO PREPARACION DETALLE)
create or replace function sp_presupuesto_prep_det_auditoria() 
returns trigger
as $$
	-- Se declaran los codigos y nombres de usuario para la tabla de auditoria
	declare
		usu_cod_old integer := (select ppc.usu_cod from presupuesto_prep_cab ppc where ppc.prpr_cod = old.prpr_cod);
		usu_login_old varchar := (select u.usu_login from usuarios u where u.usu_cod = usu_cod_old);
		usu_cod_new integer := (select ppc.usu_cod from presupuesto_prep_cab ppc where ppc.prpr_cod = new.prpr_cod);
		usu_login_new varchar := (select u.usu_login from usuarios u where u.usu_cod = usu_cod_new);
	begin
		-- Si la operacion es eliminar el registro
	    if (TG_OP = 'DELETE') then
	        insert into presupuesto_prep_det_auditoria (
				ppdaudi_operacion,
				usu_cod,
				usu_login,
				prpr_cod, 
				itm_cod,
				tipitem_cod,
				prprdet_cantidad, 
				prprdet_precio)
	        values (
				TG_OP, 
				usu_cod_old, 
				usu_login_old,
				old.prpr_cod,
				old.itm_cod,
				old.tipitem_cod,
				old.prprdet_cantidad,
				old.prprdet_precio);
		-- Si la operacion es insertar un registro
	    elseif (TG_OP = 'INSERT') then
	        insert into presupuesto_prep_det_auditoria (
				ppdaudi_operacion,
				usu_cod,
				usu_login,
				prpr_cod, 
				itm_cod,
				tipitem_cod,
				prprdet_cantidad, 
				prprdet_precio)
	        values (
				TG_OP, 
				usu_cod_new, 
				usu_login_new,
				new.prpr_cod,
				new.itm_cod,
				new.tipitem_cod,
				new.prprdet_cantidad,
				new.prprdet_precio);
	    end if;
		-- Se retorna null para cerrar la funcion
	    return null; 
	end; 
$$
language plpgsql;

create trigger tg_presupuesto_prep_det_auditoria
after insert or delete on presupuesto_prep_det
for each row execute function sp_presupuesto_prep_det_auditoria();

--tg_mediciones_det_auditoria (AUDITORIA DE MEDICIONES DETALLE)
create or replace function sp_mediciones_det_auditoria() 
returns trigger
as $$
	-- Se declaran los codigos y nombres de usuario para la tabla de auditoria
	declare
		usu_cod_old integer := (select mc.usu_cod from mediciones_cab mc where mc.med_cod = old.med_cod);
		usu_login_old varchar := (select u.usu_login from usuarios u where u.usu_cod = usu_cod_old);
		usu_cod_new integer := (select mc.usu_cod from mediciones_cab mc where mc.med_cod = new.med_cod);
		usu_login_new varchar := (select u.usu_login from usuarios u where u.usu_cod = usu_cod_new);
	begin
		-- Si la operacion es eliminar o acutalizar el registro
	    if (TG_OP in ('DELETE','UPDATE')) then
	        insert into mediciones_det_auditoria (
				mdaudi_operacion,
				usu_cod,
				usu_login,
				med_cod,
				param_cod,
				meddet_cantidad)
	        values (
				case
					when TG_OP = 'UPDATE' then TG_OP||' - REG. ANTERIOR'
					else TG_OP
				end,
				usu_cod_old, 
				usu_login_old,
				old.med_cod,
				old.param_cod,
				old.meddet_cantidad);
		end if;
		-- Si la operacion es insertar  o acutalizar un registro
	    if (TG_OP in ('INSERT', 'UPDATE')) then
	        insert into mediciones_det_auditoria (
				mdaudi_operacion,
				usu_cod,
				usu_login,
				med_cod,
				param_cod,
				meddet_cantidad)
	        values (
				case
					when TG_OP = 'UPDATE' then TG_OP||' - REG. ACTUAL'
					else TG_OP
				end,
				usu_cod_new, 
				usu_login_new,
				new.med_cod,
				new.param_cod,
				new.meddet_cantidad);
	    end if;
		-- Se retorna null para cerrar la funcion
	    return null; 
	end; 
$$
language plpgsql;

create trigger tg_mediciones_det_auditoria
after insert or delete or update on mediciones_det
for each row execute function sp_mediciones_det_auditoria();

--tg_plan_alimenticio_det_auditoria (AUDITORIA DE PLAN ALIMENTICIO DETALLE)
create or replace function sp_plan_alimenticio_det_auditoria() 
returns trigger
as $$
	-- Se declaran los codigos y nombres de usuario para la tabla de auditoria
	declare
		usu_cod_old integer := (select pac.usu_cod from plan_alimenticio_cab pac where pac.alim_cod = old.alim_cod);
		usu_login_old varchar := (select u.usu_login from usuarios u where u.usu_cod = usu_cod_old);
		usu_cod_new integer := (select pac.usu_cod from plan_alimenticio_cab pac where pac.alim_cod = new.alim_cod);
		usu_login_new varchar := (select u.usu_login from usuarios u where u.usu_cod = usu_cod_new);
	begin
		-- Si la operacion es eliminar el registro
	    if (TG_OP = 'DELETE') then
	        insert into plan_alimenticio_det_auditoria (
				padaudi_operacion,
				usu_cod,
				usu_login,
				alim_cod,
				comi_cod,
				dia_cod,
				alimdet_proteina,
				alimdet_calorias,
				alimdet_carbohidratos,
				hrcom_cod)
	        values 
				(TG_OP, 
				usu_cod_old, 
				usu_login_old,
				old.alim_cod,
				old.comi_cod,
				old.dia_cod,
				old.alimdet_proteina,
				old.alimdet_calorias,
				old.alimdet_carbohidratos,
				old.hrcom_cod);
		-- Si la operacion es insertar un registro
	    elseif (TG_OP = 'INSERT') then
	        insert into plan_alimenticio_det_auditoria (
				padaudi_operacion,
				usu_cod,
				usu_login,
				alim_cod,
				comi_cod,
				dia_cod,
				alimdet_proteina,
				alimdet_calorias,
				alimdet_carbohidratos,
				hrcom_cod)
	        values (
				TG_OP, 
				usu_cod_new, 
				usu_login_new,
				new.alim_cod,
				new.comi_cod,
				new.dia_cod,
				new.alimdet_proteina,
				new.alimdet_calorias,
				new.alimdet_carbohidratos,
				new.hrcom_cod);
	    end if;
		-- Se retorna null para cerrar la funcion
	    return null; 
	end; 
$$
language plpgsql;

create trigger tg_plan_alimenticio_det_auditoria
after insert or delete on plan_alimenticio_det
for each row execute function sp_plan_alimenticio_det_auditoria();

--tg_rutinas_det_auditoria (AUDITORIA DE RUTINAS DETALLE)
create or replace function sp_rutinas_det_auditoria() 
returns trigger
as $$
	-- Se declaran los codigos y nombres de usuario para la tabla de auditoria
	declare
		usu_cod_old integer := (select rc.usu_cod from rutinas_cab rc where rc.rut_cod = old.rut_cod);
		usu_login_old varchar := (select u.usu_login from usuarios u where u.usu_cod = usu_cod_old);
		usu_cod_new integer := (select rc.usu_cod from rutinas_cab rc where rc.rut_cod = new.rut_cod);
		usu_login_new varchar := (select u.usu_login from usuarios u where u.usu_cod = usu_cod_new);
	begin
		-- Si la operacion es eliminar el registro
	    if (TG_OP = 'DELETE') then
	        insert into rutinas_det_auditoria (
				rdaudi_operacion,
				usu_cod,
				usu_login,
				rut_cod,
				ejer_cod,
				rutdet_series,
				rutdet_repeticiones,
				dia_cod,
				equi_cod,
				tipequi_cod)
	        values 
				(TG_OP, 
				usu_cod_old, 
				usu_login_old,
				old.rut_cod,
				old.ejer_cod,
				old.rutdet_series,
				old.rutdet_repeticiones,
				old.dia_cod,
				old.equi_cod,
				old.tipequi_cod);
		-- Si la operacion es insertar un registro
	    elseif (TG_OP = 'INSERT') then
	        insert into rutinas_det_auditoria (
				rdaudi_operacion,
				usu_cod,
				usu_login,
				rut_cod,
				ejer_cod,
				rutdet_series,
				rutdet_repeticiones,
				dia_cod,
				equi_cod,
				tipequi_cod)
	        values 
				(TG_OP, 
				usu_cod_new, 
				usu_login_new,
				new.rut_cod,
				new.ejer_cod,
				new.rutdet_series,
				new.rutdet_repeticiones,
				new.dia_cod,
				new.equi_cod,
				new.tipequi_cod);
	    end if;
		-- Se retorna null para cerrar la funcion
	    return null; 
	end; 
$$
language plpgsql;

create trigger tg_rutinas_det_auditoria
after insert or delete on rutinas_det
for each row execute function sp_rutinas_det_auditoria();

--tg_evolucion_det_auditoria (Auditoria de evolucion detalle)
create or replace function sp_evolucion_det_auditoria() 
returns trigger
as $$
	-- Se declaran los codigos y nombres de usuario para la tabla de auditoria
	declare
		usu_cod_old integer := (select ec.usu_cod from evolucion_cab ec where ec.evo_cod = old.evo_cod);
		usu_login_old varchar := (select u.usu_login from usuarios u where u.usu_cod = usu_cod_old);
		usu_cod_new integer := (select ec.usu_cod from evolucion_cab ec where ec.evo_cod = new.evo_cod);
		usu_login_new varchar := (select u.usu_login from usuarios u where u.usu_cod = usu_cod_new);
	begin
		-- Si la operacion es eliminar o actualizar el registro
	    if (TG_OP in ('DELETE','UPDATE')) then
	        insert into evolucion_det_auditoria (
				edaudi_operacion,
				usu_cod,
				usu_login,
				evo_cod,
				param_cod,
				evodet_registro_ant, 
				evodet_registro_act)
	        values (
				case
					when TG_OP = 'UPDATE' then TG_OP||' - REG. ANTERIOR'
					else TG_OP
				end,
				usu_cod_old, 
				usu_login_old,
				old.evo_cod,
				old.param_cod,
				old.evodet_registro_ant,
				old.evodet_registro_act);
		end if;
		-- Si la operacion es insertar o actualizar un registro
	    if (TG_OP in ('INSERT', 'UPDATE')) then
	        insert into evolucion_det_auditoria (
				edaudi_operacion,
				usu_cod,
				usu_login,
				evo_cod,
				param_cod,
				evodet_registro_ant, 
				evodet_registro_act)
	        values (
				case
					when TG_OP = 'UPDATE' then TG_OP||' - REG. ACTUAL'
					else TG_OP
				end,
				usu_cod_new, 
				usu_login_new,
				new.evo_cod,
				new.param_cod,
				new.evodet_registro_ant,
				new.evodet_registro_act);
	    end if;
		-- Se retorna null para cerrar la funcion
	    return null; 
	end; 
$$
language plpgsql;

create trigger tg_evolucion_det_auditoria
after insert or delete or update on evolucion_det
for each row execute function sp_evolucion_det_auditoria();

--tg_asistencias_auditoria (AUDITORIA DE ASISTENCIAS)
create or replace function sp_asistencias_auditoria() 
returns trigger
as $$
	-- Se declaran los codigos y nombres de usuario para la tabla de auditoria
	declare
		usu_login_old varchar := (select u.usu_login from usuarios u where u.usu_cod = old.usu_cod);
		usu_login_new varchar := (select u.usu_login from usuarios u where u.usu_cod = new.usu_cod);
	begin
		-- Si la operacion es eliminar el registro
	    if (TG_OP = 'DELETE') then
	        insert into asistencias_auditoria (
				asisaudi_operacion,
				usu_login,
				asis_cod,
				asis_fecha,
				asis_horaentrada,
				asis_horasalida,
				cli_cod,
				usu_cod,
				suc_cod,
				emp_cod)
	        values 
				(TG_OP,  
				usu_login_old,
				old.asis_cod,
				old.asis_fecha,
				old.asis_horaentrada,
				old.asis_horasalida,
				old.cli_cod,
				old.usu_cod,
				old.suc_cod,
				old.emp_cod);
		-- Si la operacion es insertar un registro
	    elseif (TG_OP = 'INSERT') then
	        insert into asistencias_auditoria (
				asisaudi_operacion,
				usu_login,
				asis_cod,
				asis_fecha,
				asis_horaentrada,
				asis_horasalida,
				cli_cod,
				usu_cod,
				suc_cod,
				emp_cod)
	        values 
				(TG_OP,  
				usu_login_new,
				new.asis_cod,
				new.asis_fecha,
				new.asis_horaentrada,
				new.asis_horasalida,
				new.cli_cod,
				new.usu_cod,
				new.suc_cod,
				new.emp_cod);
	    end if;
		-- Se retorna null para cerrar la funcion
	    return null; 
	end; 
$$
language plpgsql;

create trigger tg_asistencias_auditoria
after insert or delete or update on asistencias
for each row execute function sp_asistencias_auditoria();
