<?php
/**
 * The template for displaying search results pages - Velki Agent Search
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package wicket
 */

get_header();
?>
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<section id="primary" class="wicket-content-area">
				<main id="main" class="wicket-site-main">

				<?php
				/**
				 * wicket_before_main_content hook
				 */
				do_action( 'wicket_before_main_content' );

				if ( have_posts() ) : ?>

					<header class="search-header" style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); border-radius: 16px; padding: 30px 40px; margin-bottom: 30px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);">
						<div style="display: flex; align-items: center; gap: 16px; margin-bottom: 12px;">
							<svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="flex-shrink: 0;">
								<circle cx="11" cy="11" r="8" stroke="#fbbf24" stroke-width="2"/>
								<path d="M21 21l-4.35-4.35" stroke="#fbbf24" stroke-width="2" stroke-linecap="round"/>
							</svg>
							<h1 class="page-title" style="color: #ffffff; font-size: 24px; margin: 0; font-weight: 600; line-height: 1.2;">
								<?php esc_html_e( 'Search Results', 'wicket' ); ?>
							</h1>
						</div>
						<div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
							<span style="color: #9ca3af; font-size: 15px;"><?php esc_html_e( 'Searching for:', 'wicket' ); ?></span>
							<span style="background: rgba(251, 191, 36, 0.1); color: #fbbf24; padding: 6px 16px; border-radius: 8px; font-size: 15px; font-weight: 600; border: 1px solid rgba(251, 191, 36, 0.2);">
								<?php echo esc_html( get_search_query() ); ?>
							</span>
						</div>
					</header><!-- .search-header -->

					<div class="velki-agent-search-results">
						<?php
						// Collect agents by their PRIMARY group only (first term), avoiding duplicates
						$agent_groups = array();
						$agents_data = array();
						$processed_agents = array(); // Track agents we've already processed

						while ( have_posts() ) :
							the_post();
							$agent_id = get_the_ID();

							// Skip if we've already processed this agent
							if ( in_array( $agent_id, $processed_agents ) ) {
								continue;
							}

							// Store agent data
							$agents_data[ $agent_id ] = array(
								'title' => get_the_title(),
								'permalink' => get_permalink(),
								'thumbnail' => get_the_post_thumbnail_url( $agent_id, 'full' ),
							);

							// Get agent group terms - USE ONLY THE FIRST/PRIMARY GROUP
							$terms = get_the_terms( $agent_id, 'agent-group' );
							if ( $terms && ! is_wp_error( $terms ) ) {
								// Take only the first term (primary group)
								$primary_term = $terms[0];
								$group_name = $primary_term->name;

								if ( ! isset( $agent_groups[ $group_name ] ) ) {
									$agent_groups[ $group_name ] = array();
								}

								// Add agent to primary group only
								$agent_groups[ $group_name ][] = $agent_id;

								// Mark agent as processed
								$processed_agents[] = $agent_id;
							}
						endwhile;

						// Display ONLY the agents found in search, grouped by their PRIMARY category
						if ( ! empty( $agent_groups ) ) {
							foreach ( $agent_groups as $group_name => $agent_ids ) {
								echo '<div class="agent-group-section" style="margin-bottom: 40px;">';
								echo '<h2 style="color: #ffffff; font-size: 22px; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #374151;">';
								echo esc_html( $group_name );
								echo '</h2>';

								// Create a custom query for ONLY these specific agent IDs
								$specific_agents_query = new WP_Query( array(
									'post_type' => 'velki-agent',
									'post__in' => $agent_ids,
									'orderby' => 'post__in', // Preserve search result order
									'posts_per_page' => -1,
								) );

								if ( $specific_agents_query->have_posts() ) {
									// Use the shortcode rendering but with specific agents
									echo '<div class="agent-modal-backdrop" id="agent-modal-backdrop-' . esc_attr($group_name) . '"></div>';
									echo '<div class="velki-agent-list-container">';

									while ( $specific_agents_query->have_posts() ) {
										$specific_agents_query->the_post();

										// Get meta fields
										$agent_post_id = get_the_ID();
										$agent_title = get_the_title();
										$agent_rating = get_post_meta( $agent_post_id, '_agent_rating', true );
										$agent_verified = get_post_meta( $agent_post_id, '_agent_verified', true );
										$agent_premium = get_post_meta( $agent_post_id, '_agent_premium', true );
										$whatsapp_url_1 = get_post_meta( $agent_post_id, '_agent_whatsapp_url_1', true );
										$whatsapp_url_2 = get_post_meta( $agent_post_id, '_agent_whatsapp_url_2', true );
										$messenger_url = get_post_meta( $agent_post_id, '_agent_messenger_url', true );
										$agent_id_meta = get_post_meta( $agent_post_id, '_agent_id', true );

										// Extract phone numbers and usernames from URLs
										$whatsapp_number_1 = $whatsapp_url_1 ? str_replace( array('https://wa.me/', 'http://wa.me/', 'wa.me/'), '', $whatsapp_url_1 ) : '';
										$whatsapp_number_2 = $whatsapp_url_2 ? str_replace( array('https://wa.me/', 'http://wa.me/', 'wa.me/'), '', $whatsapp_url_2 ) : '';
										// Extract messenger username from URL (handles m.me and facebook.com/share URLs)
										$messenger_username = '';
										if ($messenger_url) {
											if (strpos($messenger_url, 'm.me/') !== false) {
												$messenger_username = str_replace( array('https://m.me/', 'http://m.me/', 'm.me/'), '', $messenger_url );
											} else {
												// For facebook.com URLs, extract the last segment
												$path = trim(parse_url($messenger_url, PHP_URL_PATH), '/');
												$segments = explode('/', $path);
												$messenger_username = end($segments);
											}
										}

										// Get agent groups
										$agent_terms = get_the_terms( $agent_post_id, 'agent-group' );
										$agent_group = '';
										if ( $agent_terms && ! is_wp_error( $agent_terms ) ) {
											$agent_group = $agent_terms[0]->name;
										}

										$is_verified = ( $agent_verified == '1' );
										$is_premium = ( $agent_premium == '1' );

										// Generate unique ID for modal
										$unique_id = 'search-agent-' . $agent_post_id . '-' . wp_rand(1000, 9999);
										?>

										<div class="velki-agent-card">
											<div class="agent-main-section">
												<div class="agent-photo-wrapper">
													<?php if ( has_post_thumbnail() ) : ?>
														<?php the_post_thumbnail('thumbnail', array('class' => 'agent-photo')); ?>
													<?php else : ?>
														<div class="agent-photo agent-photo-placeholder">
															<span class="dashicons dashicons-businessman"></span>
														</div>
													<?php endif; ?>

													<?php if ( $is_verified ) : ?>
														<span class="agent-verified-badge">
															<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
																<path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
															</svg>
														</span>
													<?php endif; ?>
												</div>

												<div class="agent-info">
													<div class="agent-name-row">
														<h3 class="agent-name"><?php echo esc_html( $agent_title ); ?></h3>
														<?php if ( $is_premium ) : ?>
															<span class="agent-premium-crown">👑</span>
														<?php endif; ?>
													</div>

													<div class="agent-group"><?php echo esc_html( $agent_group ); ?></div>

													<?php if ( $agent_rating ) : ?>
														<div class="agent-rating">
															<?php echo str_repeat('⭐', intval($agent_rating)); ?>
														</div>
													<?php endif; ?>

													<?php if ( $agent_id_meta ) : ?>
														<div class="agent-id-section">
															<span class="agent-id-label">ID:</span>
															<span class="agent-id-value"><?php echo esc_html( $agent_id_meta ); ?></span>
														</div>
													<?php endif; ?>
												</div>

												<?php if ( $whatsapp_url_1 || $whatsapp_url_2 || $messenger_url ) : ?>
													<button class="agent-contact-toggle" data-target="<?php echo esc_attr($unique_id); ?>" aria-expanded="false" aria-label="Toggle contact info">
														<svg class="toggle-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
															<path d="M6 9l6 6 6-6"/>
														</svg>
													</button>
												<?php endif; ?>
											</div>

											<div class="agent-contact-section agent-contact-hidden" id="<?php echo esc_attr($unique_id); ?>">
												<div class="modal-header">
													<div class="modal-title-section">
														<h4 class="modal-title"><?php echo esc_html( $agent_title ); ?></h4>
														<?php if ( $agent_id_meta ) : ?>
															<div class="modal-agent-id">
																<span class="modal-id-label">ID:</span>
																<span class="modal-id-value"><?php echo esc_html( $agent_id_meta ); ?></span>
															</div>
														<?php endif; ?>
													</div>
													<button class="modal-close" data-target="<?php echo esc_attr($unique_id); ?>" aria-label="Close">
														<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
															<line x1="18" y1="6" x2="6" y2="18"></line>
															<line x1="6" y1="6" x2="18" y2="18"></line>
														</svg>
													</button>
												</div>
												<?php if ( $whatsapp_url_1 || $whatsapp_url_2 ) : ?>
													<div class="contact-column whatsapp-column">
														<div class="contact-header">
															<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
																<path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.304-1.654a11.882 11.882 0 005.713 1.456h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
															</svg>
															<span>WhatsApp</span>
														</div>

														<?php if ( $whatsapp_number_1 ) : ?>
															<div class="contact-item">
																<span class="contact-number"><?php echo esc_html( $whatsapp_number_1 ); ?></span>
																<div class="contact-actions">
																	<button class="copy-btn" data-copy="<?php echo esc_attr( $whatsapp_number_1 ); ?>" title="Copy number">
																		<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
																			<rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
																			<path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
																		</svg>
																	</button>
																	<a href="<?php echo esc_url( $whatsapp_url_1 ); ?>" target="_blank" class="message-btn whatsapp-message-btn">
																		<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
																			<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
																		</svg>
																		Message
																	</a>
																</div>
															</div>
														<?php endif; ?>

														<?php if ( $whatsapp_number_2 ) : ?>
															<div class="contact-item">
																<span class="contact-number"><?php echo esc_html( $whatsapp_number_2 ); ?></span>
																<div class="contact-actions">
																	<button class="copy-btn" data-copy="<?php echo esc_attr( $whatsapp_number_2 ); ?>" title="Copy number">
																		<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
																			<rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
																			<path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
																		</svg>
																	</button>
																	<a href="<?php echo esc_url( $whatsapp_url_2 ); ?>" target="_blank" class="message-btn whatsapp-message-btn">
																		<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
																			<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
																		</svg>
																		Message
																	</a>
																</div>
															</div>
														<?php endif; ?>
													</div>
												<?php endif; ?>

												<?php if ( $messenger_url ) : ?>
													<div class="contact-column messenger-column">
														<div class="contact-header">
															<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
																<path d="M12 0C5.373 0 0 4.975 0 11.111c0 3.497 1.745 6.616 4.472 8.652V24l4.086-2.242c1.09.301 2.246.464 3.442.464 6.627 0 12-4.974 12-11.111C24 4.975 18.627 0 12 0zm1.193 14.963l-3.056-3.259-5.963 3.259L10.732 8l3.13 3.259L19.752 8l-6.559 6.963z"/>
															</svg>
															<span>Messenger</span>
														</div>

														<div class="contact-item">
															<span class="contact-number"><?php echo esc_html( $messenger_username ); ?></span>
															<div class="contact-actions">
																<button class="copy-btn" data-copy="<?php echo esc_attr( $messenger_username ); ?>" title="Copy username">
																	<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
																		<rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
																		<path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
																	</svg>
																</button>
																<a href="<?php echo esc_url( $messenger_url ); ?>" target="_blank" class="message-btn messenger-message-btn">
																	<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
																		<line x1="22" y1="2" x2="11" y2="13"></line>
																		<polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
																	</svg>
																	Contact
																</a>
															</div>
														</div>
													</div>
												<?php endif; ?>
											</div>
										</div>

										<?php
									}

									echo '</div>'; // .velki-agent-list-container

									wp_reset_postdata();
								}

								echo '</div>'; // .agent-group-section
							}

							// Add CSS and JavaScript for agent cards
							if ( function_exists( 'velki_agent_list_inline_css' ) ) {
								velki_agent_list_inline_css();
							}
							if ( function_exists( 'velki_agent_list_inline_js' ) ) {
								velki_agent_list_inline_js();
							}
						}
						?>
					</div>

					<?php
					// Reset post data
					wp_reset_postdata();

					// Pagination
					global $wp_query;
					$total_pages = $wp_query->max_num_pages;

					if ( $total_pages > 1 ) :
						$current_page = max( 1, get_query_var( 'paged' ) );
					?>
					<div class="search-pagination" style="margin-top: 40px; display: flex; justify-content: center; align-items: center; gap: 8px; flex-wrap: wrap;">
						<?php
						// Previous button
						if ( $current_page > 1 ) :
						?>
							<a href="<?php echo esc_url( get_pagenum_link( $current_page - 1 ) ); ?>" class="pagination-btn pagination-prev" style="display: flex; align-items: center; gap: 6px; padding: 10px 16px; background: linear-gradient(135deg, #1e293b 0%, #334155 100%); color: #ffffff; text-decoration: none; border-radius: 8px; font-size: 14px; font-weight: 500; transition: all 0.2s ease; border: 1px solid rgba(255,255,255,0.1);">
								<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
									<path d="M15 18l-6-6 6-6"/>
								</svg>
								<?php esc_html_e( 'Previous', 'wicket' ); ?>
							</a>
						<?php endif; ?>

						<?php
						// Page numbers
						$range = 2; // Number of pages to show on each side of current page
						$showitems = ( $range * 2 ) + 1;

						// First page
						if ( $current_page > $range + 1 ) :
						?>
							<a href="<?php echo esc_url( get_pagenum_link( 1 ) ); ?>" class="pagination-num" style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; background: linear-gradient(135deg, #1e293b 0%, #334155 100%); color: #ffffff; text-decoration: none; border-radius: 8px; font-size: 14px; font-weight: 500; transition: all 0.2s ease; border: 1px solid rgba(255,255,255,0.1);">1</a>
							<?php if ( $current_page > $range + 2 ) : ?>
								<span style="color: #9ca3af; padding: 0 4px;">...</span>
							<?php endif; ?>
						<?php endif; ?>

						<?php
						// Page range
						for ( $i = max( 1, $current_page - $range ); $i <= min( $total_pages, $current_page + $range ); $i++ ) :
							if ( $i == $current_page ) :
							?>
								<span class="pagination-num pagination-current" style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; background: #fbbf24; color: #000000; border-radius: 8px; font-size: 14px; font-weight: 700;"><?php echo esc_html( $i ); ?></span>
							<?php else : ?>
								<a href="<?php echo esc_url( get_pagenum_link( $i ) ); ?>" class="pagination-num" style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; background: linear-gradient(135deg, #1e293b 0%, #334155 100%); color: #ffffff; text-decoration: none; border-radius: 8px; font-size: 14px; font-weight: 500; transition: all 0.2s ease; border: 1px solid rgba(255,255,255,0.1);"><?php echo esc_html( $i ); ?></a>
							<?php endif; ?>
						<?php endfor; ?>

						<?php
						// Last page
						if ( $current_page < $total_pages - $range ) :
							if ( $current_page < $total_pages - $range - 1 ) :
							?>
								<span style="color: #9ca3af; padding: 0 4px;">...</span>
							<?php endif; ?>
							<a href="<?php echo esc_url( get_pagenum_link( $total_pages ) ); ?>" class="pagination-num" style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; background: linear-gradient(135deg, #1e293b 0%, #334155 100%); color: #ffffff; text-decoration: none; border-radius: 8px; font-size: 14px; font-weight: 500; transition: all 0.2s ease; border: 1px solid rgba(255,255,255,0.1);"><?php echo esc_html( $total_pages ); ?></a>
						<?php endif; ?>

						<?php
						// Next button
						if ( $current_page < $total_pages ) :
						?>
							<a href="<?php echo esc_url( get_pagenum_link( $current_page + 1 ) ); ?>" class="pagination-btn pagination-next" style="display: flex; align-items: center; gap: 6px; padding: 10px 16px; background: linear-gradient(135deg, #1e293b 0%, #334155 100%); color: #ffffff; text-decoration: none; border-radius: 8px; font-size: 14px; font-weight: 500; transition: all 0.2s ease; border: 1px solid rgba(255,255,255,0.1);">
								<?php esc_html_e( 'Next', 'wicket' ); ?>
								<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
									<path d="M9 18l6-6-6-6"/>
								</svg>
							</a>
						<?php endif; ?>
					</div>

					<style>
					.search-pagination a:hover {
						background: linear-gradient(135deg, #334155 0%, #475569 100%) !important;
						transform: translateY(-1px);
						box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
					}
					@media (max-width: 500px) {
						.search-pagination {
							gap: 6px !important;
						}
						.search-pagination .pagination-btn {
							padding: 8px 12px !important;
							font-size: 13px !important;
						}
						.search-pagination .pagination-num {
							width: 36px !important;
							height: 36px !important;
							font-size: 13px !important;
						}
					}
					</style>
					<?php endif; ?>

				<?php else : ?>

					<div class="no-results" style="text-align: center; padding: 60px 20px;">
						<div style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); border-radius: 16px; padding: 40px; max-width: 600px; margin: 0 auto;">
							<svg width="80" height="80" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-bottom: 20px;">
								<circle cx="11" cy="11" r="8" stroke="#fbbf24" stroke-width="2"/>
								<path d="M21 21l-4.35-4.35" stroke="#fbbf24" stroke-width="2" stroke-linecap="round"/>
								<path d="M11 8v6M8 11h6" stroke="#9ca3af" stroke-width="2" stroke-linecap="round"/>
							</svg>
							<h2 style="color: #ffffff; font-size: 24px; margin-bottom: 10px;">
								<?php esc_html_e( 'No agents found', 'wicket' ); ?>
							</h2>
							<p style="color: #9ca3af; font-size: 16px; margin-bottom: 20px;">
								<?php
								/* translators: %s: search query. */
								printf( esc_html__( 'Sorry, no agents were found matching "%s". Please try a different search term.', 'wicket' ), '<strong>' . get_search_query() . '</strong>' );
								?>
							</p>
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" style="display: inline-block; background: #fbbf24; color: #000000; padding: 12px 30px; border-radius: 8px; text-decoration: none; font-weight: 600; transition: opacity 0.3s;">
								<?php esc_html_e( 'Back to Home', 'wicket' ); ?>
							</a>
						</div>
					</div>

				<?php endif;

				/**
				 * wicket_after_main_content hook
				 */
				do_action( 'wicket_after_main_content' );
				?>

				</main><!-- #main -->
			</section><!-- #primary -->
		</div>
	</div>
</div>


<?php
get_footer();
