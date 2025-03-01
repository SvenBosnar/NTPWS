<!DOCTYPE html>
<html lang="hr">
    <main>
        <section class="contact-content">
        <section class="contact-container">
            <h1>Javite nam se!</h1>
            <p>Imate pitanja? Zanimaju li vas informacije? Ispunite kontakt formular i javit ćemo Vam se u najkraćem roku!</p>
            
            <form action="#" method="post">
                <label for="ime">Ime *</label>
                <input type="text" id="ime" name="ime" required>

                <label for="prezime">Prezime *</label>
                <input type="text" id="prezime" name="prezime" required>

                <label for="email">E-mail *</label>
                <input type="email" id="email" name="email" required>


                <br><label for="drzava">Država</label>
                <select id="drzava" name="drzava">
                    <option value="hr">Hrvatska</option>
                    <option value="si">Slovenija</option>
                    <option value="rs">Srbija</option>
                    <option value="cg">Crna Gora</option>
                    <option value="mk">Makedonija</option>
                </select>

                <label for="poruka">Poruka</label>
                <textarea id="poruka" name="poruka" rows="4"></textarea>
                
                <button type="submit">Pošalji</button>
            </form>
        </section>

        <section class="map-container">
            <h2></h2>
            <p>Pronađite nas na lokaciji:</p> 
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2781.08154179024!2d15.888353875474822!3d45.80962531032617!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4765d1f8d63d6d9b%3A0xc23cfb39f593c250!2sUl.%20Mirka%20Viriusa%2016%2C%2010090%2C%20Zagreb!5e0!3m2!1sen!2shr!4v1738947355937!5m2!1sen!2shr" 
            width="600" 
            height="450" 
            style="border:0;" 
            allowfullscreen="" 
            loading="lazy" 
            referrerpolicy="no-referrer-when-downgrade">
        </iframe>
        </section>
        
    </section>
    </main>
</body>
</html>
