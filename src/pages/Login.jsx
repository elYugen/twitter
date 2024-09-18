import axios from 'axios';
import React, { useState } from 'react';

function Login() {
    const [username, setUsername] = useState('');
    const [password, setPassword] = useState('');

    const handleSubmit = async (event) => {
        event.preventDefault();
    
        try {
            const response = await axios.post('http://localhost/twitter/backend/userLogin.php', {
                username: username,
                password: password
            }, {
                headers: {
                    'Content-Type': 'application/json'
                }
            });
    
            console.log("réponse :", response.data);  
            
            if (response.data.success) {
                console.log("succès :", response.data.message); 
                window.location.href = "/home";
            } else {
                console.log("erreur :", response.data.message);
            }
        } catch (error) {
            console.log("Erreur Axios :", error);
        }
    };
    

    return (
        <div className="loginbody">
            <div className="wrapper">
                <form onSubmit={handleSubmit}>
                    <h1>Connexion</h1>
                    <div className="input-box">
                        <input type="text" placeholder="Pseudo" value={username} onChange={(e) => setUsername(e.target.value)} />
                    </div>
                    <div className="input-box">
                        <input type="password" placeholder="Mot de passe" value={password} onChange={(e) => setPassword(e.target.value)} />
                    </div>
                    <div className="remember-forgot">
                        <label><input type="checkbox" />Se souvenir de moi</label>
                        <a href="#">Mot de passe oublié</a>
                    </div>
                    <button type="submit" className="btn">Connexion</button>
                </form>
            </div>
        </div>
    );
}

export default Login;