<div class="mobile-menu-area">
	<div class="container">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="mobile-menu">
					<nav id="dropdown">
						<ul class="mobile-menu-nav">
							<li><a data-toggle="collapse" data-target="#Charts" href="#">Gestión <span class="admin-project-icon adminpro-icon adminpro-down-arrow"></span></a>
								<ul class="collapse dropdown-header-top">
									</li>
									<?php
										echo '<li><a href="index.php">Inicio</a></li>';
										if($usuario['tpc_rol_usuario'] == -1 || $usuario['tpc_rol_usuario'] == -2 || $usuario['tpc_rol_usuario'] == -3){//SI ES ALIADO ESTRATEGICO O PATROCINADOR
											echo '
												<li><a href="banners.php?opc=principal">Banners Princiaples</a>
												</li>';
											echo '
												<li><a href="banners.php?opc=secundario">Banners Secundarios</a>
												</li>';
											echo '
												<li><a href="editarme.php">Editarme</a>
												</li>';
											echo '
												<li><a href="promocionesaliados.php">Mis promociones</a>
												</li>';
											echo '
												<li><a href="filtros.php?opcion=mispatrocinados">Mis patrocinados</a>
												</li>';
										}else{
											if($usuario['tpc_rol_usuario'] == 2){//SI ES ADMIN
												echo '
												<li><a href="filtros.php?opcion=usuarios">Usuarios</a>
												</li>
												<li><a href="banners.php">Banners</a>
												</li>
												<li><a href="filtros.php?opcion=eventos">Eventos</a>
												</li>
												<li><a href="gestContenido.php?tipo=videofundacion">Video Fundación</a>
												</li>
												<li><a href="promocionesaliados.php?tpc_rol_usuario=-1">Promocioens aliados</a>
												</li>
												<li><a href="promocionesaliados.php?tpc_rol_usuario=-2">Promocioens patrocinadores</a>
												</li>';
											}
											if($usuario['tpc_rol_usuario'] != 2){//SI NO ES ADMIN
												echo '<li><a href="editarme.php">Editarme</a></li>';
											}
											echo '
											<li><a href="filtros.php?opcion=contactos">Establecimientos</a>
											</li>';
											if($usuario['tpc_rol_usuario'] == 1 || $usuario['tpc_rol_usuario'] == -4 || $usuario['tpc_rol_usuario'] == 2){//SI ES INVERSIONISTA O ADMIN
												echo '
												<li><a href="promociones.php">Promociones</a>
												</li>';
											}
											if($usuario['tpc_rol_usuario'] == 1){//SI ES INVERSIONISTA PREMIUM
												echo '
												<li><a href="banners.php?opc=secundario">Mis Banners</a>
												</li>
												<li><a href="gestContenido.php?tipo=videorse">Publicar Video</a>
												</li>';
											}
											if($usuario['tpc_rol_usuario'] == -4){//SI ES INVERSIONISTA ULTRA
												echo '
												<li><a href="banners.php?opc=secundario">Mis Banners</a>
												</li>';
											}
											//if($usuario['tpc_rol_usuario'] == 0 || $usuario['tpc_rol_usuario'] == 1){//SI ES INVERSIONISTA PAGADO O FREE
												/*echo '
												<li><a href="https://guiakmymedio.com.co/index.php?option=com_content&view=article&id=30" target="_blank">FAQs</a>
												</li>';*/
											//}
											echo '
											<li><a href="filtros.php?opcion=ayuda">Tutoriales</a>
											</li>
											<li><a href="mailto:servicioalcliente@guiakmymedio.com.co">Ayuda Tecnica</a>
											</li>';
										}
									?>
								</ul>
							</li>
							<!--<li><a data-toggle="collapse" data-target="#demo" href="#">Mailbox <span class="admin-project-icon adminpro-icon adminpro-down-arrow"></span></a>
								<ul id="demo" class="collapse dropdown-header-top">
									<li><a href="inbox.html">Inbox</a>
									</li>
									<li><a href="view-mail.html">View Mail</a>
									</li>
									<li><a href="compose-mail.html">Compose Mail</a>
									</li>
								</ul>
							</li>
							<li><a data-toggle="collapse" data-target="#others" href="#">Miscellaneous <span class="admin-project-icon adminpro-icon adminpro-down-arrow"></span></a>
								<ul id="others" class="collapse dropdown-header-top">
									<li><a href="profile.html">Profile</a>
									</li>
									<li><a href="contact-client.html">Contact Client</a>
									</li>
									<li><a href="contact-client-v.1.html">Contact Client v.1</a>
									</li>
									<li><a href="project-list.html">Project List</a>
									</li>
									<li><a href="project-details.html">Project Details</a>
									</li>
								</ul>
							</li>
							<li><a data-toggle="collapse" data-target="#Miscellaneousmob" href="#">Interface <span class="admin-project-icon adminpro-icon adminpro-down-arrow"></span></a>
								<ul id="Miscellaneousmob" class="collapse dropdown-header-top">
									<li><a href="google-map.html">Google Map</a>
									</li>
									<li><a href="data-maps.html">Data Maps</a>
									</li>
									<li><a href="pdf-viewer.html">Pdf Viewer</a>
									</li>
									<li><a href="x-editable.html">X-Editable</a>
									</li>
									<li><a href="code-editor.html">Code Editor</a>
									</li>
									<li><a href="tree-view.html">Tree View</a>
									</li>
									<li><a href="preloader.html">Preloader</a>
									</li>
									<li><a href="images-cropper.html">Images Cropper</a>
									</li>
								</ul>
							</li>
							<li><a data-toggle="collapse" data-target="#Chartsmob" href="#">Charts <span class="admin-project-icon adminpro-icon adminpro-down-arrow"></span></a>
								<ul id="Chartsmob" class="collapse dropdown-header-top">
									<li><a href="bar-charts.html">Bar Charts</a>
									</li>
									<li><a href="line-charts.html">Line Charts</a>
									</li>
									<li><a href="area-charts.html">Area Charts</a>
									</li>
									<li><a href="rounded-chart.html">Rounded Charts</a>
									</li>
									<li><a href="c3.html">C3 Charts</a>
									</li>
									<li><a href="sparkline.html">Sparkline Charts</a>
									</li>
									<li><a href="peity.html">Peity Charts</a>
									</li>
								</ul>
							</li>
							<li><a data-toggle="collapse" data-target="#Tablesmob" href="#">Tables <span class="admin-project-icon adminpro-icon adminpro-down-arrow"></span></a>
								<ul id="Tablesmob" class="collapse dropdown-header-top">
									<li><a href="static-table.html">Static Table</a>
									</li>
									<li><a href="data-table.html">Data Table</a>
									</li>
								</ul>
							</li>
							<li><a data-toggle="collapse" data-target="#formsmob" href="#">Forms <span class="admin-project-icon adminpro-icon adminpro-down-arrow"></span></a>
								<ul id="formsmob" class="collapse dropdown-header-top">
									<li><a href="basic-form-element.html">Basic Form Elements</a>
									</li>
									<li><a href="advance-form-element.html">Advanced Form Elements</a>
									</li>
									<li><a href="password-meter.html">Password Meter</a>
									</li>
									<li><a href="multi-upload.html">Multi Upload</a>
									</li>
									<li><a href="tinymc.html">Text Editor</a>
									</li>
									<li><a href="dual-list-box.html">Dual List Box</a>
									</li>
								</ul>
							</li>
							<li><a data-toggle="collapse" data-target="#Appviewsmob" href="#">App views <span class="admin-project-icon adminpro-icon adminpro-down-arrow"></span></a>
								<ul id="Appviewsmob" class="collapse dropdown-header-top">
									<li><a href="basic-form-element.html">Basic Form Elements</a>
									</li>
									<li><a href="advance-form-element.html">Advanced Form Elements</a>
									</li>
									<li><a href="password-meter.html">Password Meter</a>
									</li>
									<li><a href="multi-upload.html">Multi Upload</a>
									</li>
									<li><a href="tinymc.html">Text Editor</a>
									</li>
									<li><a href="dual-list-box.html">Dual List Box</a>
									</li>
								</ul>
							</li>
							<li><a data-toggle="collapse" data-target="#Pagemob" href="#">Pages <span class="admin-project-icon adminpro-icon adminpro-down-arrow"></span></a>
								<ul id="Pagemob" class="collapse dropdown-header-top">
									<li><a href="login.html">Login</a>
									</li>
									<li><a href="register.html">Register</a>
									</li>
									<li><a href="captcha.html">Captcha</a>
									</li>
									<li><a href="checkout.html">Checkout</a>
									</li>
									<li><a href="contact.html">Contacts</a>
									</li>
									<li><a href="review.html">Review</a>
									</li>
									<li><a href="order.html">Order</a>
									</li>
									<li><a href="comment.html">Comment</a>
									</li>
								</ul>
							</li>-->
							<li><a data-toggle="collapse" data-target="#Charts" href="cerrar_sesion.php">Cerrar Sesión <span class="admin-project-icon adminpro-icon adminpro-down-arrow"></span></a>
							</li>
						</ul>
					</nav>
				</div>
			</div>
		</div>
	</div>
</div>