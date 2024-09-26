import styles from './Feed.module.css';
import { IoChatbubbleOutline } from "react-icons/io5";
import axios from "axios";
import React, { useEffect, useState, useCallback } from 'react';

function Feed() {
  const [data, setData] = useState([]);

  const fetchPosts = useCallback(async () => {
    try {
      const response = await axios.get("http://localhost/twitter/backend/controller/GetAllPost.php");
      if (Array.isArray(response.data)) {
        setData(response.data);
        // console.log("Publication a été mis à jour:", response.data); // affiche dans la console si les publications sont mise à jour
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
              <a href={`/profile/${post.author_id}`}><img src={post.pictures} alt="user" className={styles.profilePicture}/></a>
              <div className={styles.userInfo}>
              <a href={`/profile/${post.author_id}`}><span className={styles.userInfoUsername}>{post.username}</span></a>
                <span className={styles.userInfoPublishedAt}>{post.publishdate}</span>
              </div>
            </div>
            <a href={`/read/${post.id}`}>
            <div className={styles.postContent}>
              <p>{post.content}</p>
              {post.image_id && <img src={post.image} alt="post" className={styles.postContentImage}/>}
            </div>
            <div className={styles.postIcon}>
              <a href="#"><IoChatbubbleOutline /> {post.comment_count}</a>
            </div>
            </a>
          </div>
        ))
      ) : (
        <p>Aucune publication trouvée.</p>
      )}
    </>
  );
}

export default Feed;