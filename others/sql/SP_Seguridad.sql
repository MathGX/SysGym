--sp_abm_modulos (MODULOS)
CREATE OR REPLACE FUNCTION sp_abm_modulos
(modcod integer, 
moddescri varchar, 
modestado varchar,
operacion integer,
usucod integer,
usulogin varchar,
transaccion varchar)
RETURNS void
AS $$
declare modaudit text;
begin --iniciar
	--se designan validaciones
	if operacion in (1,2) then
		perform * from modulos
		where mod_descri = upper(moddescri) and mod_cod != modcod;
		if found then
			raise exception '1';
		elseif operacion = 1 then --realizamos un insert
			INSERT INTO public.modulos
			(mod_cod, 
			mod_descri, 
			mod_estado)
			VALUES(
			modcod, 
			upper(moddescri), 
			'ACTIVO');
			raise notice 'EL MODULO FUE REGISTRADO CON EXITO';
		elseif operacion = 2 then -- realizamos un update 
			UPDATE public.modulos
			SET mod_descri=upper(moddescri), 
			mod_estado='ACTIVO'
			WHERE mod_cod=modcod;
			raise notice 'EL MODULO FUE MODIFICADO CON EXITO';
		end if;
	end if;
	if operacion = 3 then -- realizamos un update 
		update modulos
		set mod_estado = 'INACTIVO'
		WHERE mod_cod = modcod ;
		raise notice 'EL MODULO FUE BORRADO CON EXITO';
	end if;
	--se selecciona la ultima auditoria
	select coalesce(mod_audit,'') into modaudit
	from modulos
	where mod_cod = modcod;

	--se actualiza la auditoria
	update modulos
    set mod_audit = modaudit||' '||json_build_object(
        'usu_cod', usucod,
		'usu_login', usulogin,
        'fecha y hora', to_char(current_timestamp,'dd-mm-yyyy hh24:mi:ss'),
        'transaccion', upper(transaccion),
		'mod_descri', upper(moddescri), 
		'mod_estado', upper(modestado)
    )||','
    WHERE mod_cod = modcod;
end--finalizar
$$
language plpgsql;

--sp_abm_permisos (PERMISOS)
CREATE OR REPLACE FUNCTION sp_abm_permisos
(permicod integer, 
permidescri varchar, 
permiestado varchar,
operacion integer,
usucod integer,
usulogin varchar,
transaccion varchar)
RETURNS void
AS $$
declare permiaudit text;
begin --iniciar
	--se designan validaciones
	if operacion in (1,2) then
		perform * from permisos
		where permi_descri = upper(permidescri) and permi_cod != permicod;
		if found then
			raise exception '1';
		elseif operacion = 1 then --realizamos un insert
			INSERT INTO public.permisos 
			(permi_cod, 
			permi_descri, 
			permi_estado)
			VALUES(
			permicod, 
			upper(permidescri), 
			'ACTIVO');
			raise notice 'EL PERMISO FUE REGISTRADO CON EXITO';
		elseif operacion = 2 then -- realizamos un update 
			UPDATE public.permisos
			SET permi_descri=upper(permidescri), 
			permi_estado='ACTIVO'
			WHERE permi_cod=permicod;
			raise notice 'EL PERMISO FUE MODIFICADO CON EXITO';
		end if;
	end if;
	if operacion = 3 then -- realizamos un update 
		update permisos
		set permi_estado = 'INACTIVO'
		WHERE permi_cod = permicod ;
		raise notice 'EL PERMISO FUE BORRADO CON EXITO';
	end if;
	--se selecciona la ultima auditoria
	select coalesce(permi_audit,'') into permiaudit
	from permisos
	where permi_cod = permicod;

	--se actualiza la auditoria
	update permisos
    set permi_audit = permiaudit||' '||json_build_object(
        'usu_cod', usucod,
		'usu_login', usulogin,
        'fecha y hora', to_char(current_timestamp,'dd-mm-yyyy hh24:mi:ss'),
        'transaccion', upper(transaccion),
		'permi_descri', upper(permidescri), 
		'permi_estado', upper(permiestado)
    )||','
    WHERE permi_cod = permicod;
end--finalizar
$$
language plpgsql;

--sp_abm_perfiles (PERFILES)
CREATE OR REPLACE FUNCTION sp_abm_perfiles
(perfcod integer, 
perfdescri varchar, 
perfestado varchar,
operacion integer,
usucod integer,
usulogin varchar,
transaccion varchar)
RETURNS void
AS $$
declare perfaudit text;
begin --iniciar
	--se designan validaciones
	if operacion in (1,2) then
		perform * from perfiles
		where perf_descri = upper(perfdescri) and perf_cod != perfcod;
		if found then
			raise exception '1';
		elseif operacion = 1 then --realizamos un insert
			INSERT INTO public.perfiles 
			(perf_cod, 
			perf_descri, 
			perf_estado)
			VALUES(
			perfcod, 
			upper(perfdescri), 
			'ACTIVO');
			raise notice 'EL PERFIL FUE REGISTRADO CON EITO';
		elseif operacion = 2 then -- realizamos un update 
			UPDATE public.perfiles
			SET perf_descri=upper(perfdescri), 
			perf_estado='ACTIVO'
			WHERE perf_cod = perfcod;
			raise notice 'EL PERFIL FUE MODIFICADO CON EITO';
		end if;
	end if;
	if operacion = 3 then -- realizamos un update 
		update perfiles 
		set perf_estado = 'INACTIVO'
		WHERE perf_cod = perfcod ;
		raise notice 'EL PERFIL FUE BORRADO CON EXITO';
	end if;
	--se selecciona la ultima auditoria
	select coalesce(perf_audit,'') into perfaudit
	from perfiles
	where perf_cod = perfcod;

	--se actualiza la auditoria
	update perfiles
    set perf_audit = perfaudit||' '||json_build_object(
        'usu_cod', usucod,
		'usu_login', usulogin,
        'fecha y hora', to_char(current_timestamp,'dd-mm-yyyy hh24:mi:ss'),
        'transaccion', upper(transaccion),
		'perf_descri', upper(perfdescri), 
		'perf_estado', upper(perfestado)
    )||','
    WHERE perf_cod = perfcod;
end--finalizar
$$
language plpgsql;

--sp_abm_gui (GUI)
CREATE OR REPLACE FUNCTION sp_abm_gui
(guicod integer, 
modcod integer, 
guidescri varchar,
guiestado varchar,
operacion integer,
usucod integer,
usulogin varchar,
transaccion varchar,
moddescri varchar)
RETURNS void
AS $$
declare guiaudit text;
begin --iniciar
	--se designan validaciones
	if operacion in (1,2) then
		perform * from gui
		where (mod_cod = modcod and gui_descri = upper(guidescri)) and gui_cod != guicod;
		if found then
			raise exception '1';
		elseif operacion = 1 then --realizamos un insert
			INSERT INTO public.gui 
			(gui_cod, 
			mod_cod, 
			gui_descri,
			gui_estado)
			VALUES(
			guicod, 
			modcod,
			upper(guidescri), 
			'ACTIVO');
			raise notice 'EL GUI FUE REGISTRADO CON EXITO';
		elseif operacion = 2 then -- realizamos un update 
			UPDATE public.gui
			SET mod_cod = modcod,
			gui_descri=upper(guidescri), 
			gui_estado='ACTIVO'
			WHERE gui_cod = guicod;
			raise notice 'EL GUI FUE MODIFICADO CON EXITO';
		end if;
	end if;
	if operacion = 3 then -- realizamos un update 
		update gui 
		set gui_estado = 'INACTIVO'
		WHERE gui_cod = guicod ;
		raise notice 'EL GUI FUE BORRADO CON EXITO';
	end if;
	--se selecciona la ultima auditoria
	select coalesce(gui_audit,'') into guiaudit
	from gui
	where gui_cod = guicod;

	--se actualiza la auditoria
	update gui
    set gui_audit = guiaudit||' '||json_build_object(
        'usu_cod', usucod,
		'usu_login', usulogin,
        'fecha y hora', to_char(current_timestamp,'dd-mm-yyyy hh24:mi:ss'),
        'transaccion', upper(transaccion),
		'mod_cod', modcod,
		'mod_descri', upper(moddescri), 
		'gui_descri', upper(guidescri), 
		'gui_estado', upper(guiestado)
    )||','
    WHERE gui_cod = guicod;
end--finalizar
$$
language plpgsql;

CREATE OR REPLACE FUNCTION sp_abm_perfilesPermisos
(perfpermcod integer,
perfcod integer, 
permicod integer, 
perfpermestado varchar,
operacion integer,
usucod integer,
usulogin varchar,
transaccion varchar,
perfdescri varchar,
permidescri varchar)
RETURNS void
AS $$
declare perfpermaudit text;
begin --iniciar
	--se designan validaciones
	if operacion in (1,2) then
		perform * from perfiles_permisos
		where (perf_cod = perfcod and permi_cod = permicod) and perfperm_cod != perfpermcod;
		if found then
			raise exception '1';
		elseif operacion = 1 then --realizamos un insert
			INSERT INTO public.perfiles_permisos 
			(perfperm_cod, 
			perf_cod, 
			permi_cod,
			perfperm_estado)
			VALUES(
			perfpermcod,
			perfcod,
			permicod,
			'ACTIVO');
			raise notice 'PERMISO PARA ESTE PERFIL REGISTRADO EXITOSAMENTE';
		elseif operacion = 2 then -- realizamos un update 
			UPDATE public.perfiles_permisos
			SET perf_cod = perfcod, 
			permi_cod = permicod,
			perfperm_estado='ACTIVO'
			WHERE perfperm_cod = perfpermcod;
			raise notice 'PERMISO PARA ESTE PERFIL MODIFICADO EXITOSAMENTE';
		end if;
	end if;
	if operacion = 3 then -- realizamos un update 
		update perfiles_permisos 
		set perfperm_estado = 'INACTIVO'
		WHERE perfperm_cod = perfpermcod ;
		raise notice 'PERMISO PARA ESTE PERFIL BORRADO EXITOSAMENTE';
	end if;
	--se selecciona la ultima auditoria
	select coalesce(perfperm_audit,'') into perfpermaudit
	from perfiles_permisos
	where perfperm_cod = perfpermcod;

	--se actualiza la auditoria
	update perfiles_permisos
    set perfperm_audit = perfpermaudit||' '||json_build_object(
        'usu_cod', usucod,
		'usu_login', usulogin,
        'fecha y hora', to_char(current_timestamp,'dd-mm-yyyy hh24:mi:ss'),
        'transaccion', upper(transaccion),
        'perf_cod', perfcod,
		'perf_descri', upper(perfdescri), 
        'permi_cod', permicod,
		'permi_descri', upper(permidescri), 
		'perfperm_estado', upper(perfpermestado)
    )||','
    WHERE perfperm_cod = perfpermcod;
end--finalizar
$$
language plpgsql;

--sp_abm_guiPerfiles (GUI_PERFILES)
CREATE OR REPLACE FUNCTION sp_abm_guiPerfiles
(guiperfcod integer,
perfcod integer, 
guicod integer, 
modcod integer,
guiperfestado varchar,
operacion integer,
usucod integer,
usulogin varchar,
transaccion varchar,
perfdescri varchar,
guidescri varchar,
moddescri varchar)
RETURNS void
AS $$
declare guiperfaudit text;
begin --iniciar
	--se designan validaciones
	if operacion in (1,2) then
		perform * from gui_perfiles
		where (perf_cod = perfcod and gui_cod = guicod and mod_cod = modcod) and guiperf_cod != guiperfcod;
		if found then
			raise exception '1';
		elseif operacion = 1 then --realizamos un insert
			INSERT INTO public.gui_perfiles
			(guiperf_cod, 
			perf_cod, 
			gui_cod, 
			mod_cod, 
			guiperf_estado)
			VALUES(
			guiperfcod,
			perfcod,
			guicod,
			modcod,
			'ACTIVO');
			raise notice 'GUI PARA ESTE PERFIL REGISTRADO EXITOSAMENTE';
		elseif operacion = 2 then -- realizamos un update 
			UPDATE public.gui_perfiles
			SET perf_cod = perfcod, 
			gui_cod = guicod,
			mod_cod = modcod,
			guiperf_estado='ACTIVO'
			WHERE guiperf_cod = guiperfcod;
			raise notice 'GUI PARA ESTE PERFIL MODIFICADO EXITOSAMENTE';
		end if;
	end if;
	if operacion = 3 then -- realizamos un update 
		update gui_perfiles 
		set guiperf_estado = 'INACTIVO'
		WHERE guiperf_cod = guiperfcod ;
		raise notice 'GUI PARA ESTE PERFIL BORRADO EXITOSAMENTE';
	end if;
	--se selecciona la ultima auditoria
	select coalesce(guiperf_audit,'') into guiperfaudit
	from gui_perfiles
	where guiperf_cod = guiperfcod;

	--se actualiza la auditoria
	update gui_perfiles
    set guiperf_audit = guiperfaudit||' '||json_build_object(
        'usu_cod', usucod,
		'usu_login', usulogin,
        'fecha y hora', to_char(current_timestamp,'dd-mm-yyyy hh24:mi:ss'),
        'transaccion', upper(transaccion),
        'perf_cod', perfcod,
		'perf_descri', upper(perfdescri), 
        'gui_cod', guicod,
		'gui_descri', upper(guidescri), 
        'mod_cod', modcod,
		'mod_descri', upper(moddescri), 
		'guiperf_estado', upper(guiperfestado)
    )||','
    WHERE guiperf_cod = guiperfcod;
end--finalizar
$$
language plpgsql;

--sp_abm_usuarios (USUARIOS)
CREATE OR REPLACE FUNCTION sp_abm_usuarios
(usucod integer,
usulogin varchar,
usucontrasena varchar,
usuestado varchar,
perfcod integer, 
modcod integer,
funcod integer,
operacion integer,
usucodreg integer,
usuloginreg varchar,
transaccion varchar,
perfdescri varchar,
moddescri varchar,
funcionario varchar)
RETURNS void
AS $$
declare usuaudit text;
begin --iniciar
	--se designan validaciones
	if operacion = 1 then
		perform * from usuarios
		where usu_login = usulogin;
		if found then
			raise exception '1';
		elseif operacion = 1 then --realizamos un insert
			INSERT INTO public.usuarios 
			(usu_cod, 
			usu_login, 
			usu_contrasena,
			usu_fechacrea,
			perf_cod,
			mod_cod,
			fun_cod,
			usu_estado)
			VALUES(
			usucod,
			usulogin,
			md5(usucontrasena),
			current_date,
			perfcod,
			modcod,
			funcod,
			'ACTIVO');
			raise notice 'USUARIO REGISTRADO CON EXITO';
		end if;
	end if;
		if operacion = 2 then -- realizamos un update 
			perform * from usuarios
			where usu_login = usulogin and usu_cod != usucod;
			if found then 
			raise exception '1';
			elseif operacion = 2 then
			UPDATE public.usuarios 
			set usu_login = usulogin,
			usu_contrasena = md5(usucontrasena),
			perf_cod = perfcod,
			fun_cod = funcod,
			mod_cod = modcod,
			usu_estado='ACTIVO'
			WHERE usu_cod = usucod;
			raise notice 'USUARIO MODIFICADO CON EXITO';
			end if;
		end if;
	if operacion = 3 then -- realizamos un update 
		update usuarios  
		set usu_estado = 'INACTIVO'
		WHERE usu_cod = usucod ;
		raise notice 'USUARIO BORRADO CON EXITO';
	end if;
	--se selecciona la ultima auditoria
	select coalesce(usu_audit,'') into usuaudit
	from usuarios
	where usu_cod = usucod;

	--se actualiza la auditoria
	update usuarios
    set usu_audit = usuaudit||' '||json_build_object(
        'USU_COD', usucodreg,
		'USU_LOGIN', usuloginreg,
        'fecha y hora', to_char(current_timestamp,'dd-mm-yyyy hh24:mi:ss'),
        'transaccion', upper(transaccion),
		'usu_cod', usucod,
		'usu_login', usulogin,
        'perf_cod', perfcod,
		'perf_descri', upper(perfdescri), 
        'mod_cod', modcod,
		'mod_descri', upper(moddescri), 
        'fun_cod', funcod,
		'funcionario', upper(funcionario), 
		'usu_estado', upper(usuestado)
    )||','
    WHERE usu_cod = usucod;
end--finalizar
$$
language plpgsql;

--sp_abm_asigPerUsu (ASIGNACION_PERMISOS_USUARIOS)
CREATE OR REPLACE FUNCTION sp_abm_asigPerUsu
(asigusucod integer,
usucod integer, 
perfpermcod integer, 
perfcod integer,
permicod integer,
asigusuestado varchar,
operacion integer,
usucodreg integer,
usuloginreg varchar,
transaccion varchar,
usulogin varchar,
perfdescri varchar,
permidescri varchar)
RETURNS void
AS $$
declare asigusuaudit text;
begin --iniciar
	--se designan validaciones
	if operacion in (1,2) then
		perform * from asignacion_permiso_usuarios
		where (usu_cod = usucod and perfperm_cod = perfpermcod and perf_cod = perfcod and permi_cod = permicod) and asigusu_cod != asigusucod;
		if found then
			raise exception '1';
		elseif operacion = 1 then --realizamos un insert
			INSERT INTO public.asignacion_permiso_usuarios
			(asigusu_cod, 
			usu_cod, 
			perfperm_cod, 
			perf_cod,
			permi_cod, 
			asigusu_estado)
			VALUES(
			asigusucod,
			usucod,
			perfpermcod,
			perfcod,
			permicod,
			'ACTIVO');
			raise notice 'PERMISO ASIGNADO CON EXITO AL USUARIO ACORDE A SU PERFIL';
		elseif operacion = 2 then -- realizamos un update 
			UPDATE public.asignacion_permiso_usuarios 
			SET usu_cod = usucod, 
			perfperm_cod = perfpermcod,
			perf_cod = perfcod,
			permi_cod = permicod,
			asigusu_estado ='ACTIVO'
			WHERE asigusu_cod = asigusucod;
			raise notice 'PERMISO MODIFICADO CON EXITO AL USUARIO ACORDE A SU PERFIL';
		end if;
	end if;
	if operacion = 3 then -- realizamos un update 
		update asignacion_permiso_usuarios  
		set asigusu_estado  = 'INACTIVO'
		WHERE asigusu_cod = asigusucod ;
		raise notice 'PERMISO BORRADO CON EXITO AL USUARIO ACORDE A SU PERFIL';
	end if;
	--se selecciona la ultima auditoria
	select coalesce(asigusu_audit,'') into asigusuaudit
	from asignacion_permiso_usuarios
	where asigusu_cod = asigusucod;

	--se actualiza la auditoria
	update asignacion_permiso_usuarios
    set asigusu_audit = asigusuaudit||' '||json_build_object(
        'USU_COD', usucodreg,
		'USU_LOGIN', usuloginreg,
        'fecha y hora', to_char(current_timestamp,'dd-mm-yyyy hh24:mi:ss'),
        'transaccion', upper(transaccion),
		'usu_cod', usucod,
		'usu_login', usulogin,
		'perfperm_cod', perfpermcod,
		'perf_cod', perfcod,
        'perf_descri', upper(perfdescri),
		'permi_cod', permicod,
        'permi_descri', upper(permidescri),
		'asigusu_estado', upper(asigusuestado)
    )||','
    WHERE asigusu_cod = asigusucod;
end--finalizar
$$
language plpgsql;

--sp_abm_configuraciones (CONFIGURACIONES)
CREATE OR REPLACE FUNCTION sp_abm_configuraciones
(confcod integer, 
confvalidacion varchar, 
confdescri varchar, 
confestado varchar,
operacion integer,
usucod integer,
usulogin varchar,
transaccion varchar)
RETURNS void
AS $$
declare confaudit text;
begin --iniciar
	--se designan validaciones
	if operacion in (1,2) then
		perform * from configuraciones
		where conf_descri = upper(confdescri) and conf_cod != confcod;
		if found then
			raise exception '1';
		elseif operacion = 1 then --realizamos un insert
			INSERT INTO public.configuraciones
			(conf_cod, 
			conf_validacion,
			conf_descri, 
			conf_estado)
			VALUES(
			confcod, 
			confvalidacion,
			upper(confdescri), 
			'ACTIVO');
			raise notice 'LA CONFIGURACION FUE REGISTRADA CON EXITO';
		elseif operacion = 2 then -- realizamos un update 
			UPDATE public.configuraciones 
				SET conf_validacion = confvalidacion,
				conf_descri = upper(confdescri), 
				conf_estado='ACTIVO'
			WHERE conf_cod = confcod;
			raise notice 'LA CONFIGURACION FUE MODIFICADA CON EXITO';
		end if;
	end if;
	if operacion = 3 then -- realizamos un uconfiguraciones		
		update configuraciones
			set conf_estado = 'INACTIVO'
		WHERE conf_cod = confcod ;
		raise notice 'LA CONFIGURACION FUE BORRADA CON EXITO';
	end if;
	--se selecciona la ultima auditoria
	select coalesce(conf_audit,'') into confaudit
	from configuraciones
	where conf_cod = confcod;

	--se actualiza la auditoria
	update configuraciones
    set conf_audit = confaudit||' '||json_build_object(
        'usu_cod',usucod,
		'usu_login',usulogin,
        'fecha y hora',to_char(current_timestamp,'dd/mm/yyyy hh24:mi:ss'),
        'transaccion',upper(transaccion),
		'conf_validacion',confvalidacion, 
		'conf_descri',upper(confdescri), 
		'conf_estado',upper(confestado)
    )||','
    WHERE conf_cod = confcod;
end--finalizar
$$
language plpgsql;