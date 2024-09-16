import React from 'react';
import styles from './Feed.module.css';
import { IoChatbubbleOutline } from "react-icons/io5";

function Feed() {
  return(
    <>
    <div className={styles.postBox}>
      <div className={styles.postHeader}>
        <img src="https://i.pravatar.cc/300" alt="user" className={styles.profilePicture}/>
        <div className={styles.userInfo}>
          <span className={styles.userInfoUsername}>User</span>
          <span className={styles.userInfoPublishedAt}>16 Sept 2024</span>
        </div>
      </div>
      <div className={styles.postContent}>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Velit molestias blanditiis, reprehenderit unde amet id omnis. Ea placeat blanditiis explicabo, repellendus vero quidem adipisci nostrum provident error facilis, veniam repellat.</p>
        <img src="https://i.pravatar.cc/300" alt="post" className={styles.postConentImage}/>
      </div>
      <div className={styles.postIcon}>
        <a href="#"><IoChatbubbleOutline /> 14</a> {/* Nombre de commentaires */ }
      </div>
    </div>
    </>
  );
}

export default Feed;
