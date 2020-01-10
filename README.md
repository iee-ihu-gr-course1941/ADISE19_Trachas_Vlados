# ADISE19_Trachas_Vlados
# Team: Trachanas Georgios,Vladimiros Spiridis
# Project: UNO card game


# API:

/card_down/ ,GET, Επιστρέφει σε json την τελευταία κάρτα που παίχτηκε και βρίσκεται κάτω στο board.

/hand/{p} , GET, Επιστρέφει σε json τα id των καρτών που έχει στο χέρι του ο παίκτης(p). Το p παίρνει ως τιμή το username που έδωσε ο χρήστης.

/draw/{p} , GET, Επιστρέφει σε json το id, τον αριθμό και το χρώμα της κάρτας,  το οποίο θα τραβήξει ο παιχτης(p) απο το deck. Το p παίρνει ως τιμή το username που έδωσε ο χρήστης.

/start_game/, POST, Προστίθενται τα values στον πίνακα deck ανακατεμένα. Η κάρτα με το position=0 θέτετε η πρώτη κάρτα που θα είναι στο board και οι πρώτες 14 κάρτες μοιράζονται αντίστοιχα(7 και 7) στους 2 players.

/play_card/{p}/{c}, POST, Αλλάζει το status της καρτας(c) που επαιξε ο παικτης(p) σε down και παράλληλα καταγράφεται  το ποιος παικτης(played_by), επαιξε ποια καρτα(last_played) σε ποια χρονική στιγμή(last_changed). Το p παίρνει ως τιμή το username που έδωσε ο χρήστης και το c παίρνει ως τιμή το id της καρτας που έπαιξε.

/deck_ended/, POST, Θέτει την τελευταία κάρτα που παίχτηκε ως πρώτη και ο κάθε παίκτης κρατάει τις κάρτες του. Επειτα, ανακατεύει τις υπόλοιπες κάρτες που έχουν παιχτεί και τις ξανα προσθέτει στο deck.

/login/{p}/, PUT, Αποθηκεύει το username του παίκτη στον πίνακα . Αν πάνω απο 2 παίκτες προσπαθήσουν να δωσουν username τότε επιστρέφει “Error 400 No More Users Allowed”. Το p παίρνει ως τιμή το username που έδωσε ο χρήστης.

/end_game/, POST, Λήγει το παιχνίδι και κάνει reset τον πίνακα gamestatus μηδενίζοντας και σβήνοντας τα στοιχεία της βάσης.

/opponent_hand/{p}, GET, Ανάλογα με το ποιός παίκτης έκανε το call πέρνει τα id των καρτών που βρίσκονται στο χέρι του άλλου παίκτη, τα μετράει και επιστρέφει σε json τον αριθμό. Το p παίρνει ως τιμή το username που έδωσε ο χρήστης.

/get_turn/, GET, Επιστρέφει σε json ποιός παίκτης παίζει εκείνη την χρονική στιγμή. Θα επιστρέψει ανάλογα τον παίκτη την τιμή 1 ή 2.

/set_turn/{c}, POST, Θέτει την τιμή current_player, δηλαδη ποιανού σειρά είναι, ανάλογα την κάρτα ή την πράξη του παίκτη που παίζει. Αν ο παίκτης παίξει κάρτα που απαγορεύει στον αλλον να παίξει ή αλλάζει την σειρά του παιχνιδιού τότε παίζει πάλι ο ίδιος, Αν ομως παίξει οποιαδήποτε άλλη κάρτα ή πάει πάσο τότε η σειρα πάει στον άλλο παίκτη. Το c παίρνει ως τιμή το id της καρτας που έπαιξε
