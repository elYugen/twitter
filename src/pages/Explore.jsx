import Navbar from "../components/Navbar/Navbar"
import Sidebar from "../components/Sidebar/Sidebar"
import axios from "axios";
import React, { useEffect, useState } from 'react';
import styles from '../components/Sidebar/Sidebar.module.css';
function Explore() {
    const [hashtag, setHashtag] = useState([]);
  
    useEffect(() => {
      // récupérations des hashtag
      axios.get("http://localhost/twitter/backend/getAllHashtag.php", { withCredentials: true })
      .then(response => {
          setHashtag(response.data);
      })
      .catch(error => {
          console.log("Erreur lors de la récupération des catégoris:", error);
      });
    }, []);
    return(
        <>
        <div className="layout">
            <Navbar />
            <div className="container">
            <div className="postBox">
          <h2 className={styles.categoriesTitle}>Tendances :</h2>
          <hr />
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
            </div>
            <Sidebar/>
        </div>
        </>
    )
}

export default Explore