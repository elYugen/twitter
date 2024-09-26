import axios from "axios";
import React, { useEffect, useState } from 'react';
import styles from './Sidebar.module.css';

function Sidebar() {
  const [hashtag, setHashtags] = useState([]);
  const [searchTerm, setSearchTerm] = useState('');
  
  useEffect(() => {
    fetchAllHashtags();
  }, []);

  const fetchAllHashtags = () => {
    axios.get("http://localhost/twitter/backend/controller/GetAllHashtag.php", { withCredentials: true })
      .then(response => {
        setHashtags(response.data);
      })
      .catch(error => {
        console.log("Erreur lors de la récupération des hashtags:", error);
      });
  };

  const handleSearch = (event) => {
    const term = event.target.value;
    setSearchTerm(term);

    if (term.length > 0) {
      axios.get(`http://localhost/twitter/backend/controller/SearchHashtag.php?term=${term}`, { withCredentials: true })
        .then(response => {
          setHashtags(response.data);
        })
        .catch(error => {
          console.log("Erreur lors de la recherche des hashtags:", error);
        });
    } else {
      fetchAllHashtags();
    }
  };

  return(
    <>
    <div className={styles.sidebar}>
      <div className={styles.searchArticle}>
        <input type="text" value={searchTerm} onChange={handleSearch} placeholder="Rechercher un hashtag..." />
      </div>
        <div className={styles.categoriesBox}>
        <h2 className={styles.categoriesTitle}>
          {searchTerm ? "Résultat :" : "Tendances :"}
        </h2>
          {hashtag.length > 0 ? (
            hashtag.map((hash) => (
              <div key={hash.id} className={styles.categoryItem}>
                <a href={`/tendance/${hash.tag}`}>#{hash.tag}</a>
              </div>
            ))
          ) : (
            <div className={styles.categoryItem}>Aucun hashtag trouvée</div>
          )}
        </div>
      <br />
      {/* Footer ---------- */}
      <div className={styles.footerBox}>
        <p className={styles.footerContent}>© 2024 Wishtter, Inc. <br />Aucun droit réservé</p>
      </div>
    </div>
    </>
  );
}

export default Sidebar;