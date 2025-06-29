import 'package:firebase_auth/firebase_auth.dart';
import 'package:flutter/material.dart';
import 'package:tiket_konser_app/screens/home_screen.dart';
import 'package:tiket_konser_app/screens/login_screen.dart';

class AuthGate extends StatelessWidget {
  const AuthGate({super.key});

  @override
  Widget build(BuildContext context) {
    return StreamBuilder<User?>(
      // Mendengarkan perubahan status autentikasi dari Firebase
      stream: FirebaseAuth.instance.authStateChanges(),
      builder: (context, snapshot) {
        // Jika sedang dalam proses pengecekan, tampilkan loading
        if (snapshot.connectionState == ConnectionState.waiting) {
          return const Center(child: CircularProgressIndicator());
        }

        // Jika snapshot memiliki data, artinya pengguna sudah login
        if (snapshot.hasData) {
          return const HomeScreen(); // Arahkan ke Halaman Utama
        }

        // Jika tidak ada data, artinya pengguna belum login
        return LoginScreen(); // Arahkan ke Halaman Login
      },
    );
  }
}