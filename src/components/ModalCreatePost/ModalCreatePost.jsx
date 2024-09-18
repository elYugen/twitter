import React from "react";
import styles from "./ModalCreatePost.module.css";
import CreatePost from "../CreatePost/CreatePost";

function ModalCreatePost({ isOpen, onClose }) {
  return (
    <div className={`${styles.modal} ${isOpen ? styles.open : ''}`}>
      <div className={styles.modalContent}>
        <span className={styles.close} onClick={onClose}>&times;</span>
        <CreatePost />
      </div>
    </div>
  );
}

export default ModalCreatePost;
