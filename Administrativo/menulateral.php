<div class="left-sidebar-pro">
	<nav id="sidebar">
		<div class="sidebar-header">
			<a href="#">
				<?php
					$tpc_nombres_usuario_menu = explode(" ", $usuario['tpc_nombres_usuario']);
					$tpc_apellidos_usuario_menu = explode(" ", $usuario['tpc_apellidos_usuario']);
					if($usuario['tpc_imagen_usuario'] != '' && $usuario['tpc_imagen_usuario'] != 'N/A' && file_exists($usuario['tpc_imagen_usuario'])){
						$imagen = $usuario['tpc_imagen_usuario'];
					}else{
						$imagen = 'img/message/1.jpg';
					}
					echo '<img src="'.$imagen.'" alt="Foto perfil" style="height:150px;width:150px"/>';
				?>
				
			</a>
			<h3>
			<?php echo $tpc_nombres_usuario_menu[0] . '<br>' . $tpc_apellidos_usuario_menu[0]; ?></h3>
			<p>CRM GUÍA KM ½ RS</p>
			<center><a href="cambiar_foto.php"><button>Cambiar mi foto</button></a></center>
		</div>
		<div class="left-custom-menu-adp-wrap">
			<ul class="nav navbar-nav left-sidebar-menu-pro">
				<?php
					if($usuario['tpc_rol_usuario'] == -1 || $usuario['tpc_rol_usuario'] == -2 || $usuario['tpc_rol_usuario'] == -3){//SI ES ALIADO ESTRATEGICO O PATROCINADOR
						echo '<li class="nav-item"><a href="index.php" class="dropdown-item">Inicio</a><a href="banners.php?opc=principal" role="button"><span class="mini-dn">Banners Pricipales</span></a></li>';
						echo '<li class="nav-item"><a href="banners.php?opc=secundario" role="button"><span class="mini-dn">Banners Secundarios</span></a></li>';
						echo '<li class="nav-item"><a href="editarme.php" role="button"><span class="mini-dn">Editarme</span></a></li>';
						echo '<li class="nav-item"><a href="promocionesaliados.php" role="button"><span class="mini-dn">Mis promociones</span></a></li>';
						echo '<li class="nav-item"><a href="filtros.php?opcion=mispatrocinados" role="button"><span class="mini-dn">Mis patrocinados</span></a></li>';
						echo '<li class="nav-item"><a href="filtros.php?opcion=ayuda" role="button"><span class="mini-dn">Tutoriales</span></a>';
						echo '<li class="nav-item"><a href="mailto:servicioalcliente@guiakmymedio.com.co" role="button"><span class="mini-dn">Ayuda Tecnica</span></a>';
					}else{
						if($usuario['tpc_rol_usuario'] == 2){//SI ES ADMIN
							echo '
							<li class="nav-item">
								<a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="nav-link dropdown-toggle"><i class="fa big-icon fa-home"></i> <span class="mini-dn">Gestión</span> <span class="indicator-right-menu mini-dn"><i class="fa indicator-mn fa-angle-left"></i></span></a>
								<div role="menu" class="dropdown-menu left-menu-dropdown animated flipInX">
									<a href="index.php" class="dropdown-item">Inicio</a>
									<a href="filtros.php?opcion=usuarios" class="dropdown-item">Usuarios</a>
									<a href="filtros.php?opcion=eventos" class="dropdown-item">Eventos</a>
									<a href="gestContenido.php?tipo=videofundacion" class="dropdown-item">Video Fundación</a>
									<a href="promocionesaliados.php?tpc_rol_usuario=-1" class="dropdown-item">Promos Aliados</a>
									<a href="promocionesaliados.php?tpc_rol_usuario=-2" class="dropdown-item">Promos Patrocinadores</a>
								</div>
							</li>
							<li class="nav-item">
								<a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="nav-link dropdown-toggle"> <span class="mini-dn">Banners</span> <span class="indicator-right-menu mini-dn"><i class="fa indicator-mn fa-angle-left"></i></span></a>
								<div role="menu" class="dropdown-menu left-menu-dropdown animated flipInX">
									<a href="banners.php?opc=principal" class="dropdown-item">Principales</a>
									<a href="banners.php?opc=secundario" class="dropdown-item">Secundarios</a>
								</div>
							</li>';
							//echo '<li class="nav-item"><a href="banners.php" role="button"><span class="mini-dn">Banners</span></a>';
						}
						echo '
						<li class="nav-item">
							<a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="nav-link dropdown-toggle"><i class="fa big-icon fa-home"></i> <span class="mini-dn">Marketing</span> <span class="indicator-right-menu mini-dn"><i class="fa indicator-mn fa-angle-left"></i></span></a>
							<div role="menu" class="dropdown-menu left-menu-dropdown animated flipInX">
								<a href="filtros.php?opcion=contactos" class="dropdown-item">Establecimientos</a>
							</div>
						</li>';
						if($usuario['tpc_rol_usuario'] != 2){//SI NO ES ADMIN
							echo '<li class="nav-item"><a href="editarme.php" role="button"><span class="mini-dn">Editarme</span></a></li>';
						}
						echo '<li class="nav-item"><a href="estadisticas.php" role="button"><span class="mini-dn">Estadisticas</span></a>';
						if($usuario['tpc_rol_usuario'] == 1 || $usuario['tpc_rol_usuario'] == -4 || $usuario['tpc_rol_usuario'] == 2){//SI ES INVERSIONISTA O ADMIN
							echo '<li class="nav-item"><a href="promociones.php" role="button"><span class="mini-dn">Promociones</span></a>';
						}
						if($usuario['tpc_rol_usuario'] == 1){//SI ES INVERSIONISTA PREMIUM
							echo '<li class="nav-item"><a href="banners.php?opc=secundario" role="button"><span class="mini-dn">Mis Banners</span></a>';
							echo '<li class="nav-item"><a href="gestContenido.php?tipo=videorse" role="button"><span class="mini-dn">Publicar Video</span></a>';
						}
						if($usuario['tpc_rol_usuario'] == -4){//SI ES INVERSIONISTA ULTRA
							echo '<li class="nav-item"><a href="banners.php?opc=secundario" role="button"><span class="mini-dn">Mis Banners</span></a>';
						}
						//if($usuario['tpc_rol_usuario'] == 0 || $usuario['tpc_rol_usuario'] == 1){//SI ES INVERSIONISTA PAGADO O FREE
							//echo '<li class="nav-item"><a href="https://guiakmymedio.com.co/index.php?option=com_content&view=article&id=30" role="button" target="_blank"><span class="mini-dn">FAQs</span></a>';
						//}
						echo '<li class="nav-item"><a href="filtros.php?opcion=ayuda" role="button"><span class="mini-dn">Tutoriales</span></a>';
						echo '<li class="nav-item"><a href="mailto:servicioalcliente@guiakmymedio.com.co" role="button"><span class="mini-dn">Ayuda Tecnica</span></a>';
					}
				?>
				<!--<li class="nav-item"><a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="nav-link dropdown-toggle"><i class="fa big-icon fa-envelope"></i> <span class="mini-dn">Mailbox</span> <span class="indicator-right-menu mini-dn"><i class="fa indicator-mn fa-angle-left"></i></span></a>
					<div role="menu" class="dropdown-menu left-menu-dropdown animated flipInX">
						<a href="inbox.html" class="dropdown-item">Inbox</a>
						<a href="view-mail.html" class="dropdown-item">View Mail</a>
						<a href="compose-mail.html" class="dropdown-item">Compose Mail</a>
					</div>
				</li>
				<li class="nav-item"><a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="nav-link dropdown-toggle"><i class="fa big-icon fa-flask"></i> <span class="mini-dn">Interface</span> <span class="indicator-right-menu mini-dn"><i class="fa indicator-mn fa-angle-left"></i></span></a>
					<div role="menu" class="dropdown-menu left-menu-dropdown animated flipInX">
						<a href="google-map.html" class="dropdown-item">Google Map</a>
						<a href="data-maps.html" class="dropdown-item">Data Maps</a>
						<a href="pdf-viewer.html" class="dropdown-item">Pdf Viewer</a>
						<a href="x-editable.html" class="dropdown-item">X-Editable</a>
						<a href="code-editor.html" class="dropdown-item">Code Editor</a>
						<a href="tree-view.html" class="dropdown-item">Tree View</a>
						<a href="preloader.html" class="dropdown-item">Preloader</a>
						<a href="images-cropper.html" class="dropdown-item">Images Cropper</a>
					</div>
				</li>
				<li class="nav-item"><a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="nav-link dropdown-toggle"><i class="fa big-icon fa-pie-chart"></i> <span class="mini-dn">Miscellaneous</span> <span class="indicator-right-menu mini-dn"><i class="fa indicator-mn fa-angle-left"></i></span></a>
					<div role="menu" class="dropdown-menu left-menu-dropdown animated flipInX">
						<a href="profile.html" class="dropdown-item">Profile</a>
						<a href="contact-client.html" class="dropdown-item">Contact Client</a>
						<a href="contact-client-v.1.html" class="dropdown-item">Contact Client v.1</a>
						<a href="project-list.html" class="dropdown-item">Project List</a>
						<a href="project-details.html" class="dropdown-item">Project Details</a>
					</div>
				</li>
				<li class="nav-item"><a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="nav-link dropdown-toggle"><i class="fa big-icon fa-bar-chart-o"></i> <span class="mini-dn">Charts</span> <span class="indicator-right-menu mini-dn"><i class="fa indicator-mn fa-angle-left"></i></span></a>
					<div role="menu" class="dropdown-menu left-menu-dropdown chart-left-menu-std animated flipInX">
						<a href="bar-charts.html" class="dropdown-item">Bar Charts</a>
						<a href="line-charts.html" class="dropdown-item">Line Charts</a>
						<a href="area-charts.html" class="dropdown-item">Area Charts</a>
						<a href="rounded-chart.html" class="dropdown-item">Rounded Charts</a>
						<a href="c3.html" class="dropdown-item">C3 Charts</a>
						<a href="sparkline.html" class="dropdown-item">Sparkline Charts</a>
						<a href="peity.html" class="dropdown-item">Peity Charts</a>
					</div>
				</li>
				<li class="nav-item"><a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="nav-link dropdown-toggle"><i class="fa big-icon fa-table"></i> <span class="mini-dn">Data Tables</span> <span class="indicator-right-menu mini-dn"><i class="fa indicator-mn fa-angle-left"></i></span></a>
					<div role="menu" class="dropdown-menu left-menu-dropdown animated flipInX">
						<a href="static-table.html" class="dropdown-item">Static Table</a>
						<a href="data-table.html" class="dropdown-item">Data Table</a>
					</div>
				</li>
				<li class="nav-item"><a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="nav-link dropdown-toggle"><i class="fa big-icon fa-edit"></i> <span class="mini-dn">Forms Elements</span> <span class="indicator-right-menu mini-dn"><i class="fa indicator-mn fa-angle-left"></i></span></a>
					<div role="menu" class="dropdown-menu left-menu-dropdown form-left-menu-std animated flipInX">
						<a href="basic-form-element.html" class="dropdown-item">Basic Elements</a>
						<a href="advance-form-element.html" class="dropdown-item">Advance Elements</a>
						<a href="password-meter.html" class="dropdown-item">Password Meter</a>
						<a href="multi-upload.html" class="dropdown-item">Multi Upload</a>
						<a href="tinymc.html" class="dropdown-item">Text Editor</a>
						<a href="dual-list-box.html" class="dropdown-item">Dual List Box</a>
					</div>
				</li>
				<li class="nav-item"><a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="nav-link dropdown-toggle"><i class="fa big-icon fa-desktop"></i> <span class="mini-dn">App views</span> <span class="indicator-right-menu mini-dn"><i class="fa indicator-mn fa-angle-left"></i></span></a>
					<div role="menu" class="dropdown-menu left-menu-dropdown apps-left-menu-std animated flipInX">
						<a href="notifications.html" class="dropdown-item">Notifications</a>
						<a href="alerts.html" class="dropdown-item">Alerts</a>
						<a href="modals.html" class="dropdown-item">Modals</a>
						<a href="buttons.html" class="dropdown-item">Buttons</a>
						<a href="tabs.html" class="dropdown-item">Tabs</a>
						<a href="accordion.html" class="dropdown-item">Accordion</a>
						<a href="tab-menus.html" class="dropdown-item">Tab Menus</a>
					</div>
				</li>-->
				<li class="nav-item"><a href="cerrar_sesion.php" role="button"><i class="fa fa-close"></i> <span class="mini-dn">Cerrar Sesión</span></a>
				</li>
			</ul>
		</div>
	</nav>
</div>