# Shelf Control

**Sistem de Management pentru Colecții de Cărți**

## 🧾 Cuprins
- [1. Introducere](#1-introducere)  
  - [1.1 Autori](#11-autori)  
  - [1.2 Convenții](#12-convenții)  
  - [1.3 Publicul țintă](#13-publicul-țintă)  
  - [1.4 Scopul aplicației](#14-scopul-aplicației)  
- [2. Cerințe de sistem](#2-cerințe-de-sistem)  
- [3. Arhitectura aplicației](#3-arhitectura-aplicației)  
- [4. Design și UX](#4-design-și-ux)  
- [5. Detalii de implementare](#5-detalii-de-implementare)  
- [6. Documentația API](#6-documentația-api)  
- [7. Funcționalități](#7-funcționalități)  
- [8. Securitate](#8-securitate)  
- [9. Troubleshooting](#9-troubleshooting)  
- [10. Limitări și restricții](#10-limitări-și-restricții)  
- [11. Planuri viitoare](#11-planuri-viitoare)  
- [12. Referințe](#12-referințe)

---

## 1. Introducere

### 1.1 Autori  
- Crăciun Daria  
- Mercaș Ioana Elisabeta

### 1.2 Convenții  
Documentul se aliniază cu șablonul IEEE SRS și cu bune practici moderne.

### 1.3 Publicul țintă  
- **Dezvoltatori** – implementare și mentenanță  
- **Cititori/colecționari** – utilizarea aplicației  
- **Testeri QA** – validarea funcțiilor  
- **Autori de documentație** – crearea ghidurilor de utilizare

### 1.4 Scopul aplicației  
Shelf Control este o aplicație web pentru organizarea intuitivă a colecțiilor de cărți, oferind căutare, statistici, integrare API și funcții sociale.  
![obiectives](https://github.com/user-attachments/assets/4be35c1b-82e9-4696-a71b-69b3f323e6cc)

---

## 2. Cerințe de sistem

### 2.1 Server  
| Componentă        | Versiune minimă | Recomandată |
|------------------|------------------|-------------|
| PHP              | 7.4              | 8.1+        |
| Oracle DB        | 12c              | 19c+        |
| Apache/Nginx     | 2.4 / 1.18       | 2.4+ / 1.20+|
| RAM              | 2 GB             | 4 GB+       |
| Stocare          | 10 GB            | 50 GB+      |

### 2.2 Client  
- Browsere suportate: Chrome 90+, Firefox 88+, Safari 14+, Edge 90+  
- JavaScript activat și cookie-uri activate pentru autentificare JWT

### 2.3 Servicii externe  
- Google Books API (cheie validă)  
- Overpass Turbo API pentru geolocație

---

## 3. Arhitectura aplicației

Aplicație cu arhitectură **3‑tier**:

1. **Presentation Layer** – HTML5, CSS3, JS  
2. **Business Logic Layer** – PHP  
3. **Data Access Layer** – Oracle cu proceduri stocate

### 3.2 Componente  
- Servicii: autentificare (JWT), management cărți, utilizatori, integrare externă, statistici

---

## 4. Design și UX

- Principii: minimalism modern  
- Paletă de culori: roșu închis (#8B0000), alb, gri deschis/dark  
- Responsive: desktop/tablet/mobile

---

## 5. Detalii de implementare

### 5.1 Tehnologii  
- Frontend: HTML5, CSS3, JS (ES6+)  
- Backend: PHP 8.1  
- DB: Oracle 19c  
- API: Google Books v1

### 5.2 Structură bază de date  
Baza Oracle optimizată pentru relații complexe și tranzacții ACID  
![BD](https://github.com/user-attachments/assets/75d61d34-7132-4032-9a0d-74646f160ae2)

---

## 6. Documentația API

- **Autentificare**: JWT în header `Authorization: Bearer {jwt}`

### Endpoints:

#### Utilizatori  
- `POST /login` – autentificare  
- `POST /register` – creare utilizator

#### Cărți  
- `GET /filter-books` – căutare cu parametri (ex: titlu, gen)

#### Colecții  
- `POST /add-book?book_id` – adăugare carte cu status (`to_read`, `owned`, `read`)

---

## 7. Funcționalități

### 7.1 Pentru utilizatori  
- Înregistrare/login cu JWT  
  ![register](https://github.com/user-attachments/assets/77ad3e00-ee2d-4810-80d9-b814c899c921)  
- Explorare cărți via Google Books și DB  
  ![explore](https://github.com/user-attachments/assets/5408ff41-57a0-4d7a-b6d2-0dd8a74c5add)  
- Secțiuni: ToRead, Owned, My Home, Pagina cărții  
  ![toRead](https://github.com/user-attachments/assets/688bbae3-01c1-418c-ac95-925450961568)  
- Interacțiune socială: recenzii, grupuri, știri, statistici

### 7.2 Pentru administratori  
- Moderare: editare, ștergere, adăugare cărți  
  ![bookEdit](https://github.com/user-attachments/assets/d10aba13-322c-48f3-9fa0-e094984a335d)  
- Dashboard admin

---

## 8. Securitate

- **JWT** pentru autentificare stateless  
- **Role‑based access** utilizator/admin  
- **Password hashing**: bcrypt cu salt  
- **Protecție SQL injection**: query-uri parametrizate

---

## 9. Troubleshooting

Probleme comune și soluții:

- Tokens expirate  
- Conectivitate DB  
- Cookie-uri dezactivate  
- Limitări API  
- Performanță (timeout, indexare, memorie)

---

## 10. Limitări și restricții

- API limită: 1000 cereri/zi  
- Upload imagini ≤ 5 MB  
- Max 10k cărți/utilizator  
- Număr membri grupuri ≤ 50  
- Doar JPG/PNG/WebP  
- Interfață doar în română  
- Fără backup/Export automat  
- Fără notificări email/SMS

---

## 11. Planuri viitoare

- Aplicație nativă mobilă (iOS/Android)  
- Integrare cu OpenLibrary, Goodreads  
- Notificări push  
- Export CSV/Excel  
- Suport multilingv (RO, EN, FR)  
- Recomandări AI

---

## 12. Referințe

- IEEE 830‑1998, RFC 7519 (JWT), HTML5, CSS3, OWASP  
- PHP 8.1, Oracle 19c, Google Books API, MDN Web Docs  
- Goodreads, LibraryThing, StoryGraph
