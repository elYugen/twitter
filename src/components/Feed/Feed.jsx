import styles from './Feed.module.css';
import { IoChatbubbleOutline } from "react-icons/io5";
import axios from "axios";
import React, { useEffect, useState, useCallback } from 'react';

function Feed() {
  const [data, setData] = useState([]);

  const fetchPosts = useCallback(async () => {
    try {
      const response = await axios.get("http://localhost/twitter/backend/getAllPosts.php");
      if (Array.isArray(response.data)) {
        setData(response.data);
        console.log("Publication a été mis à jour:", response.data);
      } else {
        console.error("La réponse n'est pas un tableau", response.data);
      }
    } catch (error) {
      console.error("Erreur lors de la récupération des posts:", error);
    }
  }, []);

  useEffect(() => {
    fetchPosts(); 

    const intervalId = setInterval(() => {
      fetchPosts(); 
    }, 1000); // toutes les 1 secondes

    return () => clearInterval(intervalId);
  }, [fetchPosts]);

  return (
    <>
      {Array.isArray(data) && data.length > 0 ? (
        data.map((post, index) => (
          <div key={index} className={styles.postBox}>
            <div className={styles.postHeader}>
              <img src={post.pictures} alt="user" className={styles.profilePicture}/>
              <div className={styles.userInfo}>
                <span className={styles.userInfoUsername}>{post.username}</span>
                <span className={styles.userInfoPublishedAt}>{post.publishdate}</span>
              </div>
            </div>
            <div className={styles.postContent}>
              <p>{post.content}</p>
              {post.image_id && <img src={post.image} alt="post" className={styles.postContentImage}/>}
            </div>
            <div className={styles.postIcon}>
              <a href="#"><IoChatbubbleOutline /> {post.comment_count}</a>
            </div>
          </div>
        ))
      ) : (
        <p>Aucune publication trouvée.</p>
      )}
    </>
  );
}

export default Feed;