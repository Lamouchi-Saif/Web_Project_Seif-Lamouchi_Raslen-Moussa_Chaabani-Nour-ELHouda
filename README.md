# Web_Project_Seif-Lamouchi_Raslen-Moussa_Chaabani-Nour-ELHouda

# Project about Pizza sales

## Name of our Pizzaria: RT Pizza

### Presentation
- **PP:** Nour  
- **Presentation Video:** Raslen

---

## Tasks

- **Front:** Nour, Raslen & Seif  
- **Back:** Raslen & Seif  
- **Security:** Authentication, XSS, SQL injection ... ==> Seif

---

## About

### Base:

### Front USER:

- Beautiful interface
- Nav bar:  
  - Menu (→ page Menu)  
  - Make your own pizza (→ page)  
  - Cart (shows what you added)  
  - Contact (in all pages → twig integrated in all)  
  - Connexion (authentication) (integrated in login and order page) → Seif

- Pages:  
  - Presentation (index): offers / discounts on some products → Nour  
  - Connexion: form (register) {username, password, email, address, phone number} else_if (login) {email, password} → Seif  
  - Menu page: shows products → Nour (HTML/CSS)  
    - Products: margeritta, neptune... (name, ingredients, price, description, available boolean) (on menu)  
    - (POO-JS-PHP) → Raslen (don't forget it will also be on admin page, modify only if admin)  
  - Commander page (from cart): shows elements in cart - form only visible if user logged in → Raslen  
  - Make your own pizza page → ??? (LAST THING !!!!!)  

- **NOUR** our painter (CSS) !!

- **PS:** (Make sure not to create link from user to admin page!!)  
  - **Security:** no direct link between user and admin pages

---

### Front Admin:

- LOGIN only (no register) → Seif  
- CRUD system → Raslen & Seif  
- Integrate Menu page + Modifications (price etc.) → Nour (HTML) & Raslen (PHP, hidden parts)  

---

### Back:

*(When front-end is done — HTML, CSS, JS)*

- Roles system (admin-user)  
- Database / Entity Mapping (Raslen please use prepared statements to prevent SQL Injection) -> Seif/Raslen 
- Session Interface  
- Forms Manager 

---

# Status:

- Front user: 
    * By Seif:
        - pages are ready for manipulations (templates/* and src/controller/*)
        - navbar done
        - footer partially done (still need a form for contact)
        -login (partially done we need the action = ???),register functional

- Front admin: 

- Back:
    * By Seif:
        - DB is up 
        - Created all entities

