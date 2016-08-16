				<div class="subfooter">
					<div class="container">
						<div class="row">
							<div class="col-md-10">
								<p><?= __( 'ivanets_copyrights', [ '%d' => date ('Y') ] ); ?></p>
                                                                
							</div>
							<div class="col-md-2 text-xs-center text-md-right">
                                                            <small><?= __( 'ivanets_developer' ); ?></small>
							</div>
						</div>
                         <div class="row p-l-1"><?php include_once( Kohana::find_file( 'views/front/misc', 'front_misc_counters' ) );?></div>
					</div>
				</div>