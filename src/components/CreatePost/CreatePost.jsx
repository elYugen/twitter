import React from 'react';
import styles from './CreatePost.module.css';
import { CiImageOn } from "react-icons/ci";
import { MdOutlineGifBox } from "react-icons/md";

function CreatePost() {
  return(
    <>
    <div className={styles.createPost}>
      <img src="https://i.pravatar.cc/300" alt="user" className={styles.profilePicture} />
      <div className={styles.textareaContainer}>
        <textarea placeholder='Quoi de neuf docteur ?'></textarea>
        <hr />
        <div className={styles.createPostOption}>
          <div className={styles.createPostOptionAll}>
            <a href="#"><CiImageOn /></a>
            <a href="#"><MdOutlineGifBox /></a>
          </div>
          <button type="submit" name="login" style={{fontSize: "20px"}} className="button">Poster</button>
        </div>
      </div>
    </div>
    </>
  );
}

export default CreatePost;
