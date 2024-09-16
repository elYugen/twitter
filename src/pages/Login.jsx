

function Login() {
    return (
        <>
            <div className="loginbody">
                <div className="wrapper">
                    <form action="">
                        <h1>Connexion</h1>
                        <div className="input-box">
                            <input type="text" placeholder="Adresse mail" />
                            <i class='bx bxs-envelope'></i>
                        </div>
                        <div className="input-box">
                            <input type="password" placeholder="Mot de passe"/>
                            <i class='bx bxs-key' ></i>
                        </div>
                        <div className="remember-forgot">
                            <label><input type="checkbox" />Se souvenir de moi</label>
                            <a href="#">Mot de passe oublié</a>
                        </div>
                        <button type="submit" className="btn">Connexion</button>

                        <div className="register-link">
                            <p>Pas encore inscrit ? <a href="#">S'enregistrer</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </>
    );
}

export default Login