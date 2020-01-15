# ADISE19_Trachas_Vlados
# Team: Trachanas Georgios,Vladimiros Spiridis
# Project: UNO card game


# API:
### Επιστροφη καρτας του board
 /card_down/ ,[GET] , Επιστρέφει σε json την τελευταία κάρτα που παίχτηκε και βρίσκεται κάτω στο board. 
Παράδειγμα επιστροφής: {"card_id":58,"color":"green","number":"4"}


/hand/{p} , [GET] , Επιστρέφει σε json τα id των καρτών που έχει στο χέρι του ο παίκτης(p). Το p παίρνει ως τιμή το username που έδωσε ο χρήστης. 
Παράδειγμα επιστροφής: {"card_id":2,"color":"red","number":"1"},{"card_id":3,"color":"red","number":"2"},{"card_id":13,"color":"red","number":"7"},{"card_id":34,"color":"yellow","number":"5"}
 
/draw/{p} , [GET] , Επιστρέφει σε json το id, τον αριθμό και το χρώμα της κάρτας, το οποίο θα τραβήξει ο παιχτης(p) απο το deck. Το p παίρνει ως τιμή το username που έδωσε ο χρήστης.
Παράδειγμα επιστροφής: {"card_id":11,"color":"red","number":"6"}
 
/start_game/, [POST] , Προστίθενται τα values στον πίνακα deck ανακατεμένα. Η κάρτα με το position=0 θέτετε η πρώτη κάρτα που θα είναι στο board και οι πρώτες 14 κάρτες μοιράζονται αντίστοιχα(7 και 7) στους 2 players.
Παράδειγμα εισαγωγής: ../start_game/
 
/play_card/{p}/{c}, [POST] , Αλλάζει το status της καρτας(c) που επαιξε ο παικτης(p) σε down και παράλληλα καταγράφεται το ποιος παικτης(played_by), επαιξε ποια καρτα(last_played) σε ποια χρονική στιγμή(last_changed). Το p παίρνει ως τιμή το username που έδωσε ο χρήστης και το c παίρνει ως τιμή το id της καρτας που έπαιξε.
Παράδειγμα εισαγωγής: ../play_card/user/46

/deck_ended/, [POST] , Θέτει την τελευταία κάρτα που παίχτηκε ως πρώτη και ο κάθε παίκτης κρατάει τις κάρτες του. Επειτα, ανακατεύει τις υπόλοιπες κάρτες που έχουν παιχτεί και τις ξανα προσθέτει στο deck.
Παράδειγμα εισαγωγής: ../deck_ended
 
/register/{p}/, [PUT] , Αποθηκεύει το username του παίκτη στον πίνακα . Αν πάνω απο 2 παίκτες προσπαθήσουν να δωσουν username τότε επιστρέφει “Error 400 No More Users Allowed”. Το p παίρνει ως τιμή το username που έδωσε ο χρήστης.
Παράδειγμα εισαγωγής: ../register/user
 
/end_game/, POST , Λήγει το παιχνίδι και κάνει reset τον πίνακα gamestatus μηδενίζοντας και σβήνοντας τα στοιχεία της βάσης.
Παράδειγμα εισαγωγής: ../end_game
 
/opponent_hand/{p}, [GET] , Ανάλογα με το ποιός παίκτης έκανε το call πέρνει τα id των καρτών που βρίσκονται στο χέρι του άλλου παίκτη, τα μετράει και επιστρέφει σε json τον αριθμό. Το p παίρνει ως τιμή το username που έδωσε ο χρήστης. 
Παράδειγμα επιστροφής: 7
 
/get_turn/, [GET] , Επιστρέφει σε json ποιός παίκτης παίζει εκείνη την χρονική στιγμή. Θα επιστρέψει ανάλογα τον παίκτη την τιμή 1 ή 2. 
Παράδειγμα επιστροφής: "2"
 
/set_turn/{c}, [POST] , Θέτει ποιανού σειρά είναι ανάλογα την κάρτα ή την πράξη του παίκτη που παίζει. Αν ο παίκτης παίξει κάρτα που απαγορεύει στον αλλον να παίξει ή αλλάζει την σειρά του παιχνιδιού, τότε παίζει πάλι ο ίδιος, Αν ομως παίξει οποιαδήποτε άλλη κάρτα ή πάει πάσο τότε η σειρα πάει στον άλλο παίκτη. Το c παίρνει ως τιμή το id της καρτας που έπαιξε
Παράδειγμα εισαγωγής: ../set_turn/56
