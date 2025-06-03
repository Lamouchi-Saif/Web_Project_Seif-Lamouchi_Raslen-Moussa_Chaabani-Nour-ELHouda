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


---

### Front Admin:
 
- CRUD system → Raslen & Seif  
- Integrate Menu page + Modifications (price etc.) → Seif

---

### Back:


- Roles system (admin-user)  
- Database / Entity Mapping (Raslen please use prepared statements to prevent SQL Injection) -> Seif/Raslen 
- Session Interface  
- Forms Manager 

---

# Status:

- Front user: 
    * By Seif:
        - pages are ready for manipulations (templates/* and src/controller/*)
        - navbar done ,added (features for the connexion field as if it was a user or admin + cart() field)
        - footer partially done (still need a form for contact)
        - login (partially done we need the action = ???),register functional , nvm raslen Handeled these two
        - menu page is up and well structured and beautifull
        - Added Cart Functionality (dymanically(synced) adjusted with JS)(! problem we need to accept an anonymous user to add to the cart! !)
        - Contact us , is UP (couldn't configure mailer)!
        - command page is up 
        - vibrant styles
    * By Raslen:
        - Login Authenticator (automatic comparison of email and hashed password to login) and Registry form.
        - UI Adjustments, Security.yaml configured for login and logout mechanism
        - Add Product form, upload picture feature for admin ; save the picture itself in public/images, save a directory to same image into the database to manipulate.
- Front admin: 
      * By Seif :
          - It's the same as the user but with more functionalities (CRUD)()
          - Add Product page (Raslens work) but configured its Controller so that it's only visible for admin (any ACCESS with a role Different than ROLE_ADMIN he gets redirected to index)
          - Same for check_ingredients_stock only accessible by Admin
          - Configured a drop down that has links to add product and check ingredients (same principle)
          - Added edit,delete functionalities for the Admin
          - Stock Ingredient is up with all Functionalities (add,edit,delete,sorting)


- Back:
    * By Seif:
        - DB is up 
        - Created all entities
        - added cartItem Entity
        - added imageUrl field to product entity
        - fixed relations between Ingredient and IngredientStock


# Must Do (Raslen):
  - check register form validators they are not working it accepts to register a user with uncomplete email and 3 digit password(if it's gonna take alot of time drop it and i will validate them with JS even though it's not very secure(solution : vulnerable))
  - add input for repeating the password (indice: use RepeatedType::Class)
  - check for xss on the upload image(in Add Product) for the admin (again if takes much time drop it we'll figure it out)
  - then you can start on the make your own pizza or the index(presentation of our website) check above for more details
  - or configure the contact us field
## RASLEN !! VERY IMPORTANT PULL BEFORE COMMITING AND PLS IF YOU PUSH DON'T CHANGE ANY CSS OR JS OR TEMPLATES LIKE :
##  _HEADER , BASE , MENU/INDEX , ENTITY/PRODUCT , ...
## LAST PS : RUN MIGRATIONS TO HAVE CART FUNTIONALITIES
# Must Do (Raslen): 
- Render the name of the Product entity unique.
- Register - Repeat password field.
