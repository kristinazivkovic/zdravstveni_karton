import { useState, FormEvent } from 'react';
import axios from 'axios';
import { TextField, Button, Box, Paper, Typography } from '@mui/material';

export default function LoginForm() {
  const [email, setEmail] = useState<string>('');
  const [password, setPassword] = useState<string>('');
  const [error, setError] = useState<string>('');

  const handleSubmit = async (e: FormEvent) => {
    e.preventDefault();
    try {
      // Mock API call (replace with your actual backend later)
      const response = await axios.post('https://jsonplaceholder.typicode.com/posts', {
        email,
        password,
      });
      console.log('Login success:', response.data);
      alert('Login successful (mock response)');
    } catch (err) {
      setError('Failed to login');
    }
  };

  return (
    <Box display="flex" justifyContent="center" alignItems="center" minHeight="100vh">
      <Paper elevation={3} sx={{ p: 4, width: 300 }}>
       <Box sx={{ 
          display: 'flex',
          justifyContent: 'center', // Horizontal centering
          alignItems: 'center',    // Vertical centering
          minHeight: '10vh',      // Full viewport height
          textAlign: 'center'      // Fallback for text content
        }}>
          <Typography 
            variant="h4"
            component="h1"
            sx={{
              caretColor: 'transparent', // Ensures no blinking cursor
              userSelect: 'none',        // Prevents text selection
              // Additional styling:
              fontWeight: 'bold',
              color: 'primary.main',
              textTransform: 'uppercase',
              letterSpacing: '1px'
            }}
          >
            Login form
          </Typography>
        </Box>
        <TextField label="Email" fullWidth margin="normal" />
        <TextField label="Password" type="password" fullWidth margin="normal" />
        <Button variant="contained" fullWidth sx={{ mt: 2 }}>
          Login
        </Button>
      </Paper>
    </Box>
  );
}