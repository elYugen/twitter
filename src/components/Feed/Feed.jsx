import styles from './Feed.module.css';
import { IoChatbubbleOutline } from "react-icons/io5";
import axios from "axios";
import React, { useEffect, useState } from 'react';

function Feed() {
  const [data, setData] = useState([]);

  useEffect(() => {
    axios.get("http://localhost/twitter/backend/getAllPosts.php")
    .then(response => {
        if (Array.isArray(response.data)) {
            setData(response.data); 
            console.log(response.data);
            
        } else {
            console.error("La réponse n'est pas un tableau", response.data);
        }
    })
    .catch(error => {
        console.log(error);
    });
}, []);

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
              <a href="#"><IoChatbubbleOutline /> {post.comment_count}</a> {/* Nombre de commentaires */}
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
