import React from "react";
import styles from "./ModalCreatePost.module.css";

function ModalCreatePost() {
  return (
    <>
      <button id="openModal">ouvrir</button>

      <div id="postModal" class="modal">
        <div class="modalContent">
          <span class="close">&times;</span>
          <div class="postBox">
            <img src="chemin/vers/photo-profil.jpg" alt="Photo de profil" class="profilePicture"/>
            <div class="content">
              <textarea placeholder="Quoi de neuf docteur ?"></textarea>
              <hr />
            </div>
          </div>
        </div>
      </div>
    </>
  );
}
export default ModalCreatePost;
