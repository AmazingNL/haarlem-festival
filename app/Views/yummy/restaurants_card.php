<section class="page-container">
    <!--      <?php if (!empty($s['title'])): ?>
        <?= $s['title'] ?>
    <?php endif; ?>

    <?php if (!empty($s['content'])): ?>
        <?= $s['content'] ?>
    <?php endif; ?> -->
    <h2 class="restaurants-heading">All Restaurants</h2>


    <section class="restaurants-grid">
            <?php
            foreach ($resCard as $card): ?>
                <?= $card['content'] ?>
            <?php endforeach ?>

        <!-- <article class="restaurant-card">
            <section class="card-media">

                <img src="/assets/images/yummy/yummy.jpg" alt="Ratatouille">
                <section class="rating-tag">
                    <section class="rating">
                        <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M17.1595 4.30522L19.3592 8.7411C19.6592 9.35858 20.4591 9.95087 21.1341 10.0643L25.1211 10.7322C27.6709 11.1607 28.2709 13.0257 26.4335 14.8656L23.3339 17.9909C22.8089 18.5201 22.5215 19.5409 22.6839 20.2719L23.5714 24.1406C24.2712 27.2029 22.6589 28.3875 19.9717 26.787L16.2346 24.5565C15.5597 24.1532 14.4474 24.1532 13.7599 24.5565L10.0228 26.787C7.34812 28.3875 5.72331 27.1902 6.42324 24.1406L7.31064 20.2719C7.47311 19.5409 7.18565 18.5201 6.6607 17.9909L3.56105 14.8656C1.73625 13.0257 2.32369 11.1607 4.8734 10.7322L8.86046 10.0643C9.52289 9.95087 10.3228 9.35858 10.6228 8.7411L12.8225 4.30522C14.0224 1.89826 15.9721 1.89826 17.1595 4.30522Z"
                                fill="#F7D117" stroke="#AA0000" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                        <p>4.0</p>
                    </section>

                    <a class="fav" aria-label="add to favorites"> <svg class="icon-fixed icon-favorite" width="30"
                            height="30" viewBox="0 0 30 30" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M11.7634 26.2096C8.23677 23.5725 1.25 17.5435 1.25 12.118C1.25 8.53204 3.88157 5.625 7.5 5.625C9.375 5.625 11.25 6.25 13.75 8.75C16.25 6.25 18.125 5.625 20 5.625C23.6184 5.625 26.25 8.53204 26.25 12.118C26.25 17.5435 19.2633 23.5725 15.7366 26.2096C14.5499 27.097 12.9501 27.097 11.7634 26.2096Z"
                                stroke="#F7D117" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path
                                d="M13.0134 24.9596C9.48677 22.3225 2.5 16.2935 2.5 10.868C2.5 7.28204 5.13157 4.375 8.75 4.375C10.625 4.375 12.5 5 15 7.5C17.5 5 19.375 4.375 21.25 4.375C24.8684 4.375 27.5 7.28204 27.5 10.868C27.5 16.2935 20.5133 22.3225 16.9866 24.9596C15.7999 25.847 14.2001 25.847 13.0134 24.9596Z"
                                stroke="#AA0000" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </a>
                </section>
            </section>

            <section class="card-body">
                <h3 class="card-title">Ratatouille</h3>
                <p class="card-excerpt">Ratatouille Food & Wine is one of Haarlem’s top culinary destinations,
                    offering an unforgettable Michelin-starred experience.</p>
                <section class="card-tags">
                    <span>Sea Food</span>
                    <span>French</span>
                    <span>European</span>
                </section>
                <section class="card-footer">
                    <p> Available Seats</p>
                    <section class="strong">
                        <strong>52</strong>
                        <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M3.75 15V21.25M3.75 21.25H6.25C8.01776 21.25 8.90165 21.25 9.45082 21.7991C10 22.3484 10 23.2323 10 25V26.25M3.75 21.25V26.25"
                                stroke="#AA0000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path
                                d="M5 8.75L11.6459 5.10431C13.2917 4.20144 14.1148 3.75 15 3.75C15.8852 3.75 16.7083 4.20144 18.3541 5.10431L25 8.75"
                                stroke="#AA0000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M22.5 7.5V12.5M7.5 7.5V12.5" stroke="#AA0000" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round" />
                            <path
                                d="M26.25 15V21.25M26.25 21.25H23.75C21.9823 21.25 21.0984 21.25 20.5491 21.7991C20 22.3484 20 23.2323 20 25V26.25M26.25 21.25V26.25"
                                stroke="#AA0000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M8.75 17.5H15M15 17.5H21.25M15 17.5V26.25M15 26.25H13.75M15 26.25H16.25"
                                stroke="#AA0000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </section>
            </section> 
            <section class="primary-button footer-button">
                    <a>View Ratatouille</a>
                </section>
            </section>
        </article> -->

        <!-- <article class="restaurant-card">
            <section class="card-media">
                <img src="/assets/images/yummy/yummy.jpg" alt="Bistro Toujours">
                <section class="rating-tag">
                    <section class="rating">
                        <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M17.1595 4.30522L19.3592 8.7411C19.6592 9.35858 20.4591 9.95087 21.1341 10.0643L25.1211 10.7322C27.6709 11.1607 28.2709 13.0257 26.4335 14.8656L23.3339 17.9909C22.8089 18.5201 22.5215 19.5409 22.6839 20.2719L23.5714 24.1406C24.2712 27.2029 22.6589 28.3875 19.9717 26.787L16.2346 24.5565C15.5597 24.1532 14.4474 24.1532 13.7599 24.5565L10.0228 26.787C7.34812 28.3875 5.72331 27.1902 6.42324 24.1406L7.31064 20.2719C7.47311 19.5409 7.18565 18.5201 6.6607 17.9909L3.56105 14.8656C1.73625 13.0257 2.32369 11.1607 4.8734 10.7322L8.86046 10.0643C9.52289 9.95087 10.3228 9.35858 10.6228 8.7411L12.8225 4.30522C14.0224 1.89826 15.9721 1.89826 17.1595 4.30522Z"
                                fill="#F7D117" stroke="#AA0000" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                        <p>3.0</p>
                    </section>

                    <a class="fav" aria-label="Save to favorites">
                        <svg class="icon-fixed icon-favorite" width="30" height="30" viewBox="0 0 30 30"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M11.7634 26.2096C8.23677 23.5725 1.25 17.5435 1.25 12.118C1.25 8.53204 3.88157 5.625 7.5 5.625C9.375 5.625 11.25 6.25 13.75 8.75C16.25 6.25 18.125 5.625 20 5.625C23.6184 5.625 26.25 8.53204 26.25 12.118C26.25 17.5435 19.2633 23.5725 15.7366 26.2096C14.5499 27.097 12.9501 27.097 11.7634 26.2096Z"
                                stroke="#F7D117" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path
                                d="M13.0134 24.9596C9.48677 22.3225 2.5 16.2935 2.5 10.868C2.5 7.28204 5.13157 4.375 8.75 4.375C10.625 4.375 12.5 5 15 7.5C17.5 5 19.375 4.375 21.25 4.375C24.8684 4.375 27.5 7.28204 27.5 10.868C27.5 16.2935 20.5133 22.3225 16.9866 24.9596C15.7999 25.847 14.2001 25.847 13.0134 24.9596Z"
                                stroke="#AA0000" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </a>
                </section>
            </section>

            <section class="card-body">
                <h3 class="card-title">Bistro Toujours</h3>
                <p class="card-excerpt">Bistro Toujours captures the charm of a classic French bistro while
                    adding its own modern Haarlem identity.</p>
                <section class="card-tags">
                    <span>Sea Food</span>
                    <span>Dutch</span>
                    <span>European</span>
                </section>
                <section class="card-footer">
                    <p> Available Seats</p>
                    <section class="strong">
                        <strong>48</strong>
                        <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M3.75 15V21.25M3.75 21.25H6.25C8.01776 21.25 8.90165 21.25 9.45082 21.7991C10 22.3484 10 23.2323 10 25V26.25M3.75 21.25V26.25"
                                stroke="#AA0000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path
                                d="M5 8.75L11.6459 5.10431C13.2917 4.20144 14.1148 3.75 15 3.75C15.8852 3.75 16.7083 4.20144 18.3541 5.10431L25 8.75"
                                stroke="#AA0000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M22.5 7.5V12.5M7.5 7.5V12.5" stroke="#AA0000" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round" />
                            <path
                                d="M26.25 15V21.25M26.25 21.25H23.75C21.9823 21.25 21.0984 21.25 20.5491 21.7991C20 22.3484 20 23.2323 20 25V26.25M26.25 21.25V26.25"
                                stroke="#AA0000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M8.75 17.5H15M15 17.5H21.25M15 17.5V26.25M15 26.25H13.75M15 26.25H16.25"
                                stroke="#AA0000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </section>
                </section>
                <section class="primary-button footer-button">
                    <a>View Toujours</a>
                </section>
            </section>
        </article>

        <article class="restaurant-card">
            <section class="card-media">
                <img src="/assets/images/yummy/yummy.jpg" alt="New Vegas">
                <section class="rating-tag">
                    <section class="rating">
                        <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M17.1595 4.30522L19.3592 8.7411C19.6592 9.35858 20.4591 9.95087 21.1341 10.0643L25.1211 10.7322C27.6709 11.1607 28.2709 13.0257 26.4335 14.8656L23.3339 17.9909C22.8089 18.5201 22.5215 19.5409 22.6839 20.2719L23.5714 24.1406C24.2712 27.2029 22.6589 28.3875 19.9717 26.787L16.2346 24.5565C15.5597 24.1532 14.4474 24.1532 13.7599 24.5565L10.0228 26.787C7.34812 28.3875 5.72331 27.1902 6.42324 24.1406L7.31064 20.2719C7.47311 19.5409 7.18565 18.5201 6.6607 17.9909L3.56105 14.8656C1.73625 13.0257 2.32369 11.1607 4.8734 10.7322L8.86046 10.0643C9.52289 9.95087 10.3228 9.35858 10.6228 8.7411L12.8225 4.30522C14.0224 1.89826 15.9721 1.89826 17.1595 4.30522Z"
                                fill="#F7D117" stroke="#AA0000" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                        <p>3.0</p>
                    </section>

                    <a class="fav" aria-label="Save to favorites">
                        <svg class="icon-fixed icon-favorite" width="30" height="30" viewBox="0 0 30 30"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M11.7634 26.2096C8.23677 23.5725 1.25 17.5435 1.25 12.118C1.25 8.53204 3.88157 5.625 7.5 5.625C9.375 5.625 11.25 6.25 13.75 8.75C16.25 6.25 18.125 5.625 20 5.625C23.6184 5.625 26.25 8.53204 26.25 12.118C26.25 17.5435 19.2633 23.5725 15.7366 26.2096C14.5499 27.097 12.9501 27.097 11.7634 26.2096Z"
                                stroke="#F7D117" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path
                                d="M13.0134 24.9596C9.48677 22.3225 2.5 16.2935 2.5 10.868C2.5 7.28204 5.13157 4.375 8.75 4.375C10.625 4.375 12.5 5 15 7.5C17.5 5 19.375 4.375 21.25 4.375C24.8684 4.375 27.5 7.28204 27.5 10.868C27.5 16.2935 20.5133 22.3225 16.9866 24.9596C15.7999 25.847 14.2001 25.847 13.0134 24.9596Z"
                                stroke="#AA0000" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </a>
                </section>
            </section>

            <section class="card-body">
                <h3 class="card-title">New Vegas</h3>
                <p class="card-excerpt">New Vegas brings a fresh and modern twist to vegetarian cuisine. With
                    creative dishes full of color, texture, and flavor.</p>
                <section class="card-tags">
                    <span>Vegan</span>
                </section>
                <section class="card-footer">
                    <p> Available Seats</p>
                    <section class="strong">
                        <strong>36</strong>
                        <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M3.75 15V21.25M3.75 21.25H6.25C8.01776 21.25 8.90165 21.25 9.45082 21.7991C10 22.3484 10 23.2323 10 25V26.25M3.75 21.25V26.25"
                                stroke="#AA0000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path
                                d="M5 8.75L11.6459 5.10431C13.2917 4.20144 14.1148 3.75 15 3.75C15.8852 3.75 16.7083 4.20144 18.3541 5.10431L25 8.75"
                                stroke="#AA0000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M22.5 7.5V12.5M7.5 7.5V12.5" stroke="#AA0000" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round" />
                            <path
                                d="M26.25 15V21.25M26.25 21.25H23.75C21.9823 21.25 21.0984 21.25 20.5491 21.7991C20 22.3484 20 23.2323 20 25V26.25M26.25 21.25V26.25"
                                stroke="#AA0000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M8.75 17.5H15M15 17.5H21.25M15 17.5V26.25M15 26.25H13.75M15 26.25H16.25"
                                stroke="#AA0000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </section>
                </section>
                <section class="primary-button footer-button">
                    <a>View New Vegas</a>
                </section>
            </section>
        </article>

        <article class="restaurant-card">
            <section class="card-media">
                <img src="/assets/images/yummy/yummy.jpg" alt="Grand Cafe Brinkman">
                <section class="rating-tag">
                    <section class="rating">
                        <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M17.1595 4.30522L19.3592 8.7411C19.6592 9.35858 20.4591 9.95087 21.1341 10.0643L25.1211 10.7322C27.6709 11.1607 28.2709 13.0257 26.4335 14.8656L23.3339 17.9909C22.8089 18.5201 22.5215 19.5409 22.6839 20.2719L23.5714 24.1406C24.2712 27.2029 22.6589 28.3875 19.9717 26.787L16.2346 24.5565C15.5597 24.1532 14.4474 24.1532 13.7599 24.5565L10.0228 26.787C7.34812 28.3875 5.72331 27.1902 6.42324 24.1406L7.31064 20.2719C7.47311 19.5409 7.18565 18.5201 6.6607 17.9909L3.56105 14.8656C1.73625 13.0257 2.32369 11.1607 4.8734 10.7322L8.86046 10.0643C9.52289 9.95087 10.3228 9.35858 10.6228 8.7411L12.8225 4.30522C14.0224 1.89826 15.9721 1.89826 17.1595 4.30522Z"
                                fill="#F7D117" stroke="#AA0000" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                        <p>3.0</p>
                    </section>

                    <a class="fav" aria-label="Save to favorites">
                        <svg class="icon-fixed icon-favorite" width="30" height="30" viewBox="0 0 30 30"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M11.7634 26.2096C8.23677 23.5725 1.25 17.5435 1.25 12.118C1.25 8.53204 3.88157 5.625 7.5 5.625C9.375 5.625 11.25 6.25 13.75 8.75C16.25 6.25 18.125 5.625 20 5.625C23.6184 5.625 26.25 8.53204 26.25 12.118C26.25 17.5435 19.2633 23.5725 15.7366 26.2096C14.5499 27.097 12.9501 27.097 11.7634 26.2096Z"
                                stroke="#F7D117" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path
                                d="M13.0134 24.9596C9.48677 22.3225 2.5 16.2935 2.5 10.868C2.5 7.28204 5.13157 4.375 8.75 4.375C10.625 4.375 12.5 5 15 7.5C17.5 5 19.375 4.375 21.25 4.375C24.8684 4.375 27.5 7.28204 27.5 10.868C27.5 16.2935 20.5133 22.3225 16.9866 24.9596C15.7999 25.847 14.2001 25.847 13.0134 24.9596Z"
                                stroke="#AA0000" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </a>
                </section>
            </section>

            <section class="card-body">
                <h3 class="card-title">Grand Cafe Brinkman</h3>
                <p class="card-excerpt">Grand Café Brinkman is one of Haarlem’s most iconic gathering places,
                    located right on the Grote Markt.</p>
                <section class="card-tags">
                    <span>Modern</span>
                    <span>Dutch</span>
                </section>
                <section class="card-footer">
                    <p> Available Seats</p>
                    <section class="strong">
                        <strong>100</strong>
                        <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M3.75 15V21.25M3.75 21.25H6.25C8.01776 21.25 8.90165 21.25 9.45082 21.7991C10 22.3484 10 23.2323 10 25V26.25M3.75 21.25V26.25"
                                stroke="#AA0000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path
                                d="M5 8.75L11.6459 5.10431C13.2917 4.20144 14.1148 3.75 15 3.75C15.8852 3.75 16.7083 4.20144 18.3541 5.10431L25 8.75"
                                stroke="#AA0000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M22.5 7.5V12.5M7.5 7.5V12.5" stroke="#AA0000" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round" />
                            <path
                                d="M26.25 15V21.25M26.25 21.25H23.75C21.9823 21.25 21.0984 21.25 20.5491 21.7991C20 22.3484 20 23.2323 20 25V26.25M26.25 21.25V26.25"
                                stroke="#AA0000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M8.75 17.5H15M15 17.5H21.25M15 17.5V26.25M15 26.25H13.75M15 26.25H16.25"
                                stroke="#AA0000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </section>
                </section>
                <section class="primary-button footer-button">
                    <a>View Grand Cafe Brinkman</a>
                </section>
            </section>
        </article>

        <article class="restaurant-card">
            <section class="card-media">
                <img src="/assets/images/yummy/yummy.jpg" alt="Cafe de Roemer">
                <section class="rating-tag">
                    <section class="rating">
                        <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M17.1595 4.30522L19.3592 8.7411C19.6592 9.35858 20.4591 9.95087 21.1341 10.0643L25.1211 10.7322C27.6709 11.1607 28.2709 13.0257 26.4335 14.8656L23.3339 17.9909C22.8089 18.5201 22.5215 19.5409 22.6839 20.2719L23.5714 24.1406C24.2712 27.2029 22.6589 28.3875 19.9717 26.787L16.2346 24.5565C15.5597 24.1532 14.4474 24.1532 13.7599 24.5565L10.0228 26.787C7.34812 28.3875 5.72331 27.1902 6.42324 24.1406L7.31064 20.2719C7.47311 19.5409 7.18565 18.5201 6.6607 17.9909L3.56105 14.8656C1.73625 13.0257 2.32369 11.1607 4.8734 10.7322L8.86046 10.0643C9.52289 9.95087 10.3228 9.35858 10.6228 8.7411L12.8225 4.30522C14.0224 1.89826 15.9721 1.89826 17.1595 4.30522Z"
                                fill="#F7D117" stroke="#AA0000" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                        <p>4.0</p>
                    </section>

                    <a class="fav" aria-label="Save to favorites">
                        <svg class="icon-fixed icon-favorite" width="30" height="30" viewBox="0 0 30 30"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M11.7634 26.2096C8.23677 23.5725 1.25 17.5435 1.25 12.118C1.25 8.53204 3.88157 5.625 7.5 5.625C9.375 5.625 11.25 6.25 13.75 8.75C16.25 6.25 18.125 5.625 20 5.625C23.6184 5.625 26.25 8.53204 26.25 12.118C26.25 17.5435 19.2633 23.5725 15.7366 26.2096C14.5499 27.097 12.9501 27.097 11.7634 26.2096Z"
                                stroke="#F7D117" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path
                                d="M13.0134 24.9596C9.48677 22.3225 2.5 16.2935 2.5 10.868C2.5 7.28204 5.13157 4.375 8.75 4.375C10.625 4.375 12.5 5 15 7.5C17.5 5 19.375 4.375 21.25 4.375C24.8684 4.375 27.5 7.28204 27.5 10.868C27.5 16.2935 20.5133 22.3225 16.9866 24.9596C15.7999 25.847 14.2001 25.847 13.0134 24.9596Z"
                                stroke="#AA0000" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </a>
                </section>
            </section>

            <section class="card-body">
                <h3 class="card-title">Café de Roemer</h3>
                <p class="card-excerpt">Café de Roemer is a warm and inviting café-bar offering a mix of
                    seafood and European dishes.</p>
                <section class="card-tags">
                    <span>Sea Food</span>
                    <span>Dutch</span>
                </section>
                <section class="card-footer">
                    <p> Available Seats</p>
                    <section class="strong">
                        <strong>35</strong>
                        <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M3.75 15V21.25M3.75 21.25H6.25C8.01776 21.25 8.90165 21.25 9.45082 21.7991C10 22.3484 10 23.2323 10 25V26.25M3.75 21.25V26.25"
                                stroke="#AA0000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path
                                d="M5 8.75L11.6459 5.10431C13.2917 4.20144 14.1148 3.75 15 3.75C15.8852 3.75 16.7083 4.20144 18.3541 5.10431L25 8.75"
                                stroke="#AA0000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M22.5 7.5V12.5M7.5 7.5V12.5" stroke="#AA0000" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round" />
                            <path
                                d="M26.25 15V21.25M26.25 21.25H23.75C21.9823 21.25 21.0984 21.25 20.5491 21.7991C20 22.3484 20 23.2323 20 25V26.25M26.25 21.25V26.25"
                                stroke="#AA0000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M8.75 17.5H15M15 17.5H21.25M15 17.5V26.25M15 26.25H13.75M15 26.25H16.25"
                                stroke="#AA0000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </section>
                </section>
                <section class="primary-button footer-button">
                    <a>View Café de Roemer</a>
                </section>
            </section>
        </article>

        <article class="restaurant-card">
            <section class="card-media">
                <img src="/assets/images/yummy/yummy.jpg" alt="Restaurant Fris">
                <section class="rating-tag">
                    <section class="rating">
                        <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M17.1595 4.30522L19.3592 8.7411C19.6592 9.35858 20.4591 9.95087 21.1341 10.0643L25.1211 10.7322C27.6709 11.1607 28.2709 13.0257 26.4335 14.8656L23.3339 17.9909C22.8089 18.5201 22.5215 19.5409 22.6839 20.2719L23.5714 24.1406C24.2712 27.2029 22.6589 28.3875 19.9717 26.787L16.2346 24.5565C15.5597 24.1532 14.4474 24.1532 13.7599 24.5565L10.0228 26.787C7.34812 28.3875 5.72331 27.1902 6.42324 24.1406L7.31064 20.2719C7.47311 19.5409 7.18565 18.5201 6.6607 17.9909L3.56105 14.8656C1.73625 13.0257 2.32369 11.1607 4.8734 10.7322L8.86046 10.0643C9.52289 9.95087 10.3228 9.35858 10.6228 8.7411L12.8225 4.30522C14.0224 1.89826 15.9721 1.89826 17.1595 4.30522Z"
                                fill="#F7D117" stroke="#AA0000" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                        <p>3.0</p>
                    </section>

                    <a class="fav" aria-label="Save to favorites">
                        <svg class="icon-fixed icon-favorite" width="30" height="30" viewBox="0 0 30 30"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M11.7634 26.2096C8.23677 23.5725 1.25 17.5435 1.25 12.118C1.25 8.53204 3.88157 5.625 7.5 5.625C9.375 5.625 11.25 6.25 13.75 8.75C16.25 6.25 18.125 5.625 20 5.625C23.6184 5.625 26.25 8.53204 26.25 12.118C26.25 17.5435 19.2633 23.5725 15.7366 26.2096C14.5499 27.097 12.9501 27.097 11.7634 26.2096Z"
                                stroke="#F7D117" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path
                                d="M13.0134 24.9596C9.48677 22.3225 2.5 16.2935 2.5 10.868C2.5 7.28204 5.13157 4.375 8.75 4.375C10.625 4.375 12.5 5 15 7.5C17.5 5 19.375 4.375 21.25 4.375C24.8684 4.375 27.5 7.28204 27.5 10.868C27.5 16.2935 20.5133 22.3225 16.9866 24.9596C15.7999 25.847 14.2001 25.847 13.0134 24.9596Z"
                                stroke="#AA0000" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </a>
                </section>
            </section>

            <section class="card-body">
                <h3 class="card-title">Restaurant Fris</h3>
                <p class="card-excerpt">Fris brings a fresh and modern twist to vegetarian cuisine. Creative
                    dishes full of color, texture, and flavor.</p>
                <section class="card-tags">
                    <span>French</span>
                    <span>Dutch</span>
                </section>
                <section class="card-footer">
                    <p> Available Seats</p>
                    <section class="strong">
                        <strong>45</strong>
                        <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M3.75 15V21.25M3.75 21.25H6.25C8.01776 21.25 8.90165 21.25 9.45082 21.7991C10 22.3484 10 23.2323 10 25V26.25M3.75 21.25V26.25"
                                stroke="#AA0000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path
                                d="M5 8.75L11.6459 5.10431C13.2917 4.20144 14.1148 3.75 15 3.75C15.8852 3.75 16.7083 4.20144 18.3541 5.10431L25 8.75"
                                stroke="#AA0000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M22.5 7.5V12.5M7.5 7.5V12.5" stroke="#AA0000" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round" />
                            <path
                                d="M26.25 15V21.25M26.25 21.25H23.75C21.9823 21.25 21.0984 21.25 20.5491 21.7991C20 22.3484 20 23.2323 20 25V26.25M26.25 21.25V26.25"
                                stroke="#AA0000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M8.75 17.5H15M15 17.5H21.25M15 17.5V26.25M15 26.25H13.75M15 26.25H16.25"
                                stroke="#AA0000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </section>
                </section>
                <section class="primary-button footer-button">
                    <a>View Restaurant Fris</a>
                </section>
            </section>
        </article>

        <article class="restaurant-card single-col">
            <section class="card-media">
                <img src="/assets/images/yummy/yummy.jpg" alt="Restaurant ML">
                <section class="rating-tag">
                    <section class="rating">
                        <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M17.1595 4.30522L19.3592 8.7411C19.6592 9.35858 20.4591 9.95087 21.1341 10.0643L25.1211 10.7322C27.6709 11.1607 28.2709 13.0257 26.4335 14.8656L23.3339 17.9909C22.8089 18.5201 22.5215 19.5409 22.6839 20.2719L23.5714 24.1406C24.2712 27.2029 22.6589 28.3875 19.9717 26.787L16.2346 24.5565C15.5597 24.1532 14.4474 24.1532 13.7599 24.5565L10.0228 26.787C7.34812 28.3875 5.72331 27.1902 6.42324 24.1406L7.31064 20.2719C7.47311 19.5409 7.18565 18.5201 6.6607 17.9909L3.56105 14.8656C1.73625 13.0257 2.32369 11.1607 4.8734 10.7322L8.86046 10.0643C9.52289 9.95087 10.3228 9.35858 10.6228 8.7411L12.8225 4.30522C14.0224 1.89826 15.9721 1.89826 17.1595 4.30522Z"
                                fill="#F7D117" stroke="#AA0000" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                        <p>4.0</p>
                    </section>

                    <a class="fav" id="favBtn" aria-label="Save to favorites">
                        <svg class="icon-fixed icon-favorite" width="30" height="30" viewBox="0 0 30 30"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M11.7634 26.2096C8.23677 23.5725 1.25 17.5435 1.25 12.118C1.25 8.53204 3.88157 5.625 7.5 5.625C9.375 5.625 11.25 6.25 13.75 8.75C16.25 6.25 18.125 5.625 20 5.625C23.6184 5.625 26.25 8.53204 26.25 12.118C26.25 17.5435 19.2633 23.5725 15.7366 26.2096C14.5499 27.097 12.9501 27.097 11.7634 26.2096Z"
                                stroke="#F7D117" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path
                                d="M13.0134 24.9596C9.48677 22.3225 2.5 16.2935 2.5 10.868C2.5 7.28204 5.13157 4.375 8.75 4.375C10.625 4.375 12.5 5 15 7.5C17.5 5 19.375 4.375 21.25 4.375C24.8684 4.375 27.5 7.28204 27.5 10.868C27.5 16.2935 20.5133 22.3225 16.9866 24.9596C15.7999 25.847 14.2001 25.847 13.0134 24.9596Z"
                                stroke="#AA0000" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </a>
                </section>
            </section>

            <section class="card-body">
                <h3 class="card-title">Restaurant ML</h3>
                <p class="card-excerpt">Restaurant ML offers a refined dining experience in the heart of
                    Haarlem, known for its elegant atmosphere and beautifully crafted dishes.</p>
                <section class="card-tags">
                    <span>Sea Food</span>
                    <span>Dutch</span>
                </section>
                <section class="card-footer">
                    <p> Available Seats</p>
                    <section class="strong">
                        <strong>60</strong>
                        <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M3.75 15V21.25M3.75 21.25H6.25C8.01776 21.25 8.90165 21.25 9.45082 21.7991C10 22.3484 10 23.2323 10 25V26.25M3.75 21.25V26.25"
                                stroke="#AA0000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path
                                d="M5 8.75L11.6459 5.10431C13.2917 4.20144 14.1148 3.75 15 3.75C15.8852 3.75 16.7083 4.20144 18.3541 5.10431L25 8.75"
                                stroke="#AA0000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M22.5 7.5V12.5M7.5 7.5V12.5" stroke="#AA0000" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round" />
                            <path
                                d="M26.25 15V21.25M26.25 21.25H23.75C21.9823 21.25 21.0984 21.25 20.5491 21.7991C20 22.3484 20 23.2323 20 25V26.25M26.25 21.25V26.25"
                                stroke="#AA0000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M8.75 17.5H15M15 17.5H21.25M15 17.5V26.25M15 26.25H13.75M15 26.25H16.25"
                                stroke="#AA0000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </section>
                </section>
                <section class="primary-button footer-button">
                    <a>View Restaurant ML</a>
                </section>
            </section>
        </article>  -->
    </section>
</section>

<div id="detailPageError" class="popup-overlay" style="display:none">

    <div class="detail-page-error">
        <div class="component-10-parent">
            <div class="component-10">
                <img class="vector-icon64" alt="">

                <img class="vector-icon65" alt="">

            </div>
            <div class="the-detail-page-is-not-availab-wrapper">
                <div class="the-detail-page">The detail page is not available yet</div>
            </div>
        </div>
        <div class="button-component-116">
            <div class="component-102">
                <img class="vector-icon65" alt="">

            </div>
            <div class="label8">Close</div>
        </div>
    </div>




    <!-- 
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const KEY = "favorites";

            function getFav() {
                try { return JSON.parse(localStorage.getItem(KEY) || "[]"); }
                catch (e) { return []; }
            }

            function saveFav(fav) {
                localStorage.setItem(KEY, JSON.stringify(fav));
            }

            function saveFavorite(item) {
                const fav = getFav();
                const page = item || { title: document.title, url: location.href };

                // Check duplicates by title and url
                if (!fav.some(f => f.title === page.title && f.url === page.url)) {
                    fav.push(page);
                    saveFav(fav);
                }
            }

            const favButtons = document.querySelectorAll('.fav svg');
            favButtons.forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();

                    const savedFav = localStorage.getItem("favorites");

                    // if (icon) {
                    //     icon.classList.toggle('active');

                        // Fill the SVG paths when active, restore when inactive
                        // const elements = icon.querySelectorAll('.icon-favorite');
                        // if (icon.classList.contains('active')) {
                        //     icon.setAttribute('fill', '#d3cece');
                                                        
                        // }
                        // else {
                        //     elements.forEach(el => {
                        //         if (el.dataset.originalFill !== undefined) {
                        //             if (el.dataset.originalFill) el.setAttribute('fill', el.dataset.originalFill);
                        //             else el.removeAttribute('fill');
                        //         }
                        //         if (el.dataset.originalStroke !== undefined) {
                        //             if (el.dataset.originalStroke) el.setAttribute('stroke', el.dataset.originalStroke);
                        //             else el.removeAttribute('stroke');
                        //         }
                        //     });
                        // }
                    }

                    // Find the restaurant card and extract data
                    const card = btn.closest('.restaurant-card');
                    const title = card?.querySelector('.card-title')?.textContent.trim() || document.title;

                    // Attempt to get a meaningful URL; fallback to a fragment with title
                    let url = null;
                    const viewLink = card?.querySelector('.primary-button a[href]');
                    if (viewLink && viewLink.getAttribute('href')) {
                        url = viewLink.href;
                    } else {
                        // Use page URL + fragment so favorites are unique per-card
                        url = location.href.split('#')[0] + '#fav-' + encodeURIComponent(title);
                    }

                    saveFavorite({ title, url });
                });
            });
        });
    </script> -->