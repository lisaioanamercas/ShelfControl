# Shelf Control

**Sistem de Management pentru Colecții de Cărți**

---

## 1. Introducere

### Autori  
- Crăciun Daria  
- Mercaș Ioana Elisabeta

### Convenții  
Documentul urmează șablonul IEEE SRS și bune practici moderne.

### Publicul țintă  
- **Dezvoltatori** – implementare și mentenanță  
- **Cititori/colecționari** – utilizarea aplicației  
- **Testeri QA** – validarea funcțiilor  
- **Autori de documentație** – crearea ghidurilor

### Scopul aplicației  
Shelf Control este o aplicație web pentru organizarea intuitivă a colecțiilor de cărți, oferind căutare, statistici, integrare API și funcții sociale.

---

## 2. Cerințe de sistem

### Server  
| Componentă    | Min. | Recomandat |
|---------------|------|-------------|
| PHP           | 7.4  | 8.1+        |
| Oracle DB     | 12c  | 19c+        |
| Apache/Nginx  | 2.4 / 1.18 | 2.4+ / 1.20+ |
| RAM           | 2 GB | 4 GB+       |
| Stocare       | 10 GB| 50 GB+      |

### Client  
- Browsere: Chrome 90+, Firefox 88+, Safari 14+, Edge 90+  
- Necesită: JavaScript activ, cookie-uri activate (pentru JWT)

### Servicii externe  
- Google Books API  
- Overpass Turbo API

---

## 3. Arhitectura aplicației

Shelf Control are o arhitectură 3-tier:

1. **Presentation Layer** – HTML5, CSS3, JavaScript  
2. **Business Logic Layer** – PHP  
3. **Data Access Layer** – Oracle DB + proceduri stocate

![Arhitectura aplicației](images/arhitectura-aplicatie.png)

### Componente backend:
- Autentificare (JWT)
- Gestionare cărți
- Utilizatori
- Statistici
- Integrare cu servicii externe

---

## 4. Design și UX

- Stil: minimalist, modern
- Culori: roșu închis `#8B0000`, alb, gri
- Responsive: suport complet desktop/tablet/mobil

![Design UX](images/ux-mockup.png)

---

## 5. Detalii de implementare

### Tehnologii  
- Frontend: HTML5, CSS3, JS (ES6+)  
- Backend: PHP 8.1  
- DB: Oracle 19c  
- API: Google Books v1

### Structură bază de date  
Baza de date Oracle este optimizată pentru relații complexe și integritate tranzacțională (ACID).

![Diagrama ER](images/erd-diagrama.png)

---

## 6. Documentația API

### Autentificare  
JWT trimis în header:  
```http
Authorization: Bearer {jwt_token}
