<?php
//$str = 'eyJKX3Byb3NfMTUxNjI3MDMzODEyMyI6eyJjYWxsbmFtZSI6InBhZ2VJbWciLCJpZHMiOnsicHJvcyI6IkpfcHJvc18xNTE2MjcwMzM4MTIzIiwiZWxlIjoiSl9lbGVfMTUxNjI3MDMzODEyMyIsImNsbyI6IkpfZWxlX2Nsb18xNTE2MjcwMzM4MTIzIn0sInZhbHVlcyI6eyJpbWciOiIvL3p0Mm0uenRnYW1lLmNvbS91cGxvYWQvenQybS9pbWFnZXMvY2xwLzIwMTgtMDEvMTgvNWE2MDczMmQ0ZDYzZC5wbmciLCJsaW5rIjoiIiwicG9zIjoidG9wIiwiZGlzdCI6IjAifX0sIkpfcHJvc18xNTE2MjcwMzgzODkyIjp7ImNhbGxuYW1lIjoicGFnZUltZyIsImlkcyI6eyJwcm9zIjoiSl9wcm9zXzE1MTYyNzAzODM4OTIiLCJlbGUiOiJKX2VsZV8xNTE2MjcwMzgzODkyIiwiY2xvIjoiSl9lbGVfY2xvXzE1MTYyNzAzODM4OTIifSwidmFsdWVzIjp7ImltZyI6Ii8venQybS56dGdhbWUuY29tL3VwbG9hZC96dDJtL2ltYWdlcy9jbHAvMjAxOC0wMS8xOC81YTYwNzMzNDAzZjJmLmpwZyIsImxpbmsiOiIiLCJwb3MiOiJubyIsImRpc3QiOiIwIn19LCJKX3Byb3NfMTUxNjI3MDM5MDEyNCI6eyJjYWxsbmFtZSI6InBhZ2VJbWciLCJpZHMiOnsicHJvcyI6IkpfcHJvc18xNTE2MjcwMzkwMTI0IiwiZWxlIjoiSl9lbGVfMTUxNjI3MDM5MDEyNCIsImNsbyI6IkpfZWxlX2Nsb18xNTE2MjcwMzkwMTI0In0sInZhbHVlcyI6eyJpbWciOiIvL3p0Mm0uenRnYW1lLmNvbS91cGxvYWQvenQybS9pbWFnZXMvY2xwLzIwMTgtMDEvMTgvNWE2MDczM2FhOGJmOS5naWYiLCJsaW5rIjoiIiwicG9zIjoibm8iLCJkaXN0IjoiMCJ9fSwiSl9wcm9zXzE1MTYyNzA0MDA2MzYiOnsiY2FsbG5hbWUiOiJwYWdlSW1nIiwiaWRzIjp7InByb3MiOiJKX3Byb3NfMTUxNjI3MDQwMDYzNiIsImVsZSI6IkpfZWxlXzE1MTYyNzA0MDA2MzYiLCJjbG8iOiJKX2VsZV9jbG9fMTUxNjI3MDQwMDYzNiJ9LCJ2YWx1ZXMiOnsiaW1nIjoiLy96dDJtLnp0Z2FtZS5jb20vdXBsb2FkL3p0Mm0vaW1hZ2VzL2NscC8yMDE4LTAxLzE4LzVhNjA3NzViMDgxZTEuanBnIiwibGluayI6IiIsInBvcyI6Im5vIiwiZGlzdCI6IjAifX0sIkpfcHJvc18xNTE2MzMwNTM1MzgxIjp7ImNhbGxuYW1lIjoicGFnZVNsaWRlIiwiaWRzIjp7InByb3MiOiJKX3Byb3NfMTUxNjMzMDUzNTM4MSIsImVsZSI6IkpfZWxlXzE1MTYzMzA1MzUzODEiLCJjbG8iOiJKX2VsZV9jbG9fMTUxNjMzMDUzNTM4MSJ9LCJ2YWx1ZXMiOnsidHlwZSI6ImNvdmVyZmxvdyIsImF1dG9wbGF5IjoidHJ1ZSIsImltZ0FyciI6WyJodHRwOi8vY2xwLnp0Z2FtZS5jb20vaW1hZ2VzL3NsaWRlX2ltZzIucG5nIiwiaHR0cDovL2NscC56dGdhbWUuY29tL2ltYWdlcy9zbGlkZV9pbWcyLnBuZyIsImh0dHA6Ly9jbHAuenRnYW1lLmNvbS9pbWFnZXMvc2xpZGVfaW1nMi5wbmciLCJodHRwOi8vY2xwLnp0Z2FtZS5jb20vaW1hZ2VzL3NsaWRlX2ltZzIucG5nIl0sImJhY2tncm91bmQiOiJodHRwOi8vY2xwLnp0Z2FtZS5jb20vaW1hZ2VzL3NsaWRlX2JnLnBuZyIsIndpZHRoIjoyNjcsImhlaWdodCI6NjAwfX0sIkpfcHJvc18xNTE2MzQ5MjI2OTgwIjp7ImNhbGxuYW1lIjoicGFnZUltZyIsImlkcyI6eyJwcm9zIjoiSl9wcm9zXzE1MTYzNDkyMjY5ODAiLCJlbGUiOiJKX2VsZV8xNTE2MzQ5MjI2OTgwIiwiY2xvIjoiSl9lbGVfY2xvXzE1MTYzNDkyMjY5ODAifSwidmFsdWVzIjp7ImltZyI6Ii8venQybS56dGdhbWUuY29tL3VwbG9hZC96dDJtL2ltYWdlcy9jbHAvMjAxOC0wMS8xOS81YTYxYTc0NTMyOWMwLnBuZyIsImxpbmsiOiIiLCJwb3MiOiJ0b3AiLCJkaXN0IjoiMCJ9fSwiSl9wcm9zXzE1MTYzNDkyNTU4MjAiOnsiY2FsbG5hbWUiOiJwYWdlSW1nIiwiaWRzIjp7InByb3MiOiJKX3Byb3NfMTUxNjM0OTI1NTgyMCIsImVsZSI6IkpfZWxlXzE1MTYzNDkyNTU4MjAiLCJjbG8iOiJKX2VsZV9jbG9fMTUxNjM0OTI1NTgyMCJ9LCJ2YWx1ZXMiOnsiaW1nIjoiLy96dDJtLnp0Z2FtZS5jb20vdXBsb2FkL3p0Mm0vaW1hZ2VzL2NscC8yMDE4LTAxLzE5LzVhNjFhNzUxYjIxNzEuanBnIiwibGluayI6IiIsInBvcyI6Im5vIiwiZGlzdCI6IjAifX0sIkpfcHJvc18xNTE2MzQ5Mjg2MTczIjp7ImNhbGxuYW1lIjoicGFnZVNsaWRlIiwiaWRzIjp7InByb3MiOiJKX3Byb3NfMTUxNjM0OTI4NjE3MyIsImVsZSI6IkpfZWxlXzE1MTYzNDkyODYxNzMiLCJjbG8iOiJKX2VsZV9jbG9fMTUxNjM0OTI4NjE3MyJ9LCJ2YWx1ZXMiOnsidHlwZSI6InNsaWRlIiwiYXV0b3BsYXkiOiJ0cnVlIiwiaW1nQXJyIjpbIiUyRiUyRnp0Mm0uenRnYW1lLmNvbSUyRnVwbG9hZCUyRnp0Mm0lMkZpbWFnZXMlMkZjbHAlMkYyMDE4LTAxJTJGMTklMkY1YTYxYTc3ODJlMjE3LmpwZyIsIiUyRiUyRnp0Mm0uenRnYW1lLmNvbSUyRnVwbG9hZCUyRnp0Mm0lMkZpbWFnZXMlMkZjbHAlMkYyMDE4LTAxJTJGMTklMkY1YTYxYTkyZTJmY2ZmLmpwZyIsIiUyRiUyRnp0Mm0uenRnYW1lLmNvbSUyRnVwbG9hZCUyRnp0Mm0lMkZpbWFnZXMlMkZjbHAlMkYyMDE4LTAxJTJGMTklMkY1YTYxYTk0OGNiMDUzLmpwZyIsIiUyRiUyRnp0Mm0uenRnYW1lLmNvbSUyRnVwbG9hZCUyRnp0Mm0lMkZpbWFnZXMlMkZjbHAlMkYyMDE4LTAxJTJGMTklMkY1YTYxYTk0ZDFiMTQ2LmpwZyIsIiUyRiUyRnp0Mm0uenRnYW1lLmNvbSUyRnVwbG9hZCUyRnp0Mm0lMkZpbWFnZXMlMkZjbHAlMkYyMDE4LTAxJTJGMTklMkY1YTYxYTk1MGEwNzJhLmpwZyJdLCJiYWNrZ3JvdW5kIjoiaHR0cDovL2NscC56dGdhbWUuY29tL2ltYWdlcy9zbGlkZV9iZy5wbmciLCJ3aWR0aCI6MjUwLCJoZWlnaHQiOjExODF9fSwiSl9wcm9zXzE1MTYzNDk3Nzg0NzEiOnsiY2FsbG5hbWUiOiJwYWdlSW1nIiwiaWRzIjp7InByb3MiOiJKX3Byb3NfMTUxNjM0OTc3ODQ3MSIsImVsZSI6IkpfZWxlXzE1MTYzNDk3Nzg0NzEiLCJjbG8iOiJKX2VsZV9jbG9fMTUxNjM0OTc3ODQ3MSJ9LCJ2YWx1ZXMiOnsiaW1nIjoiLy96dDJtLnp0Z2FtZS5jb20vdXBsb2FkL3p0Mm0vaW1hZ2VzL2NscC8yMDE4LTAxLzE5LzVhNjFhOTVhNDhhNmMuanBnIiwibGluayI6IiIsInBvcyI6Im5vIiwiZGlzdCI6IjAifX0sIkpfcHJvc18xNTE2MzQ5NzkzNjEzIjp7ImNhbGxuYW1lIjoicGFnZUJ0biIsImlkcyI6eyJwcm9zIjoiSl9wcm9zXzE1MTYzNDk3OTM2MTMiLCJlbGUiOiJKX2VsZV8xNTE2MzQ5NzkzNjEzIiwiY2xvIjoiSl9lbGVfY2xvXzE1MTYzNDk3OTM2MTMifSwidmFsdWVzIjp7ImJ0biI6Ii8venQybS56dGdhbWUuY29tL3VwbG9hZC96dDJtL2ltYWdlcy9jbHAvMjAxOC0wMS8xOS81YTYxYTk2ODg4OTQ1LmpwZyIsImJ0bkxpbmsiOiJodHRwOi8vd3d3Lnp0Z2FtZS5jb20iLCJwb3MiOiJ0b3AiLCJkaXN0Ijo5NjZ9fSwiSl9wcm9zXzE1MTYzNTE4MzU1MDciOnsiY2FsbG5hbWUiOiJwYWdlSW1nIiwiaWRzIjp7InByb3MiOiJKX3Byb3NfMTUxNjM1MTgzNTUwNyIsImVsZSI6IkpfZWxlXzE1MTYzNTE4MzU1MDciLCJjbG8iOiJKX2VsZV9jbG9fMTUxNjM1MTgzNTUwNyJ9LCJ2YWx1ZXMiOnsiaW1nIjoiLy96dDJtLnp0Z2FtZS5jb20vdXBsb2FkL3p0Mm0vaW1hZ2VzL2NscC8yMDE4LTAxLzE5LzVhNjFiMTY4NDZmMjIucG5nIiwibGluayI6Imh0dHA6Ly96dDJjaGFubmVsZG93bi56dGdhbWUuY29tLmNuLzI4NzUxMjAuYXBrIiwicG9zIjoidG9wIiwiZGlzdCI6IjAifX0sIkpfcHJvc18xNTE2MzUxODUxOTgwIjp7ImNhbGxuYW1lIjoicGFnZUltZyIsImlkcyI6eyJwcm9zIjoiSl9wcm9zXzE1MTYzNTE4NTE5ODAiLCJlbGUiOiJKX2VsZV8xNTE2MzUxODUxOTgwIiwiY2xvIjoiSl9lbGVfY2xvXzE1MTYzNTE4NTE5ODAifSwidmFsdWVzIjp7ImltZyI6Ii8venQybS56dGdhbWUuY29tL3VwbG9hZC96dDJtL2ltYWdlcy9jbHAvMjAxOC0wMS8xOS81YTYxYjE3MmJkNDA0LmpwZyIsImxpbmsiOiIiLCJwb3MiOiJubyIsImRpc3QiOiIwIn19LCJKX3Byb3NfMTUxNjM1MTg2MTk3OSI6eyJjYWxsbmFtZSI6InBhZ2VJbWciLCJpZHMiOnsicHJvcyI6IkpfcHJvc18xNTE2MzUxODYxOTc5IiwiZWxlIjoiSl9lbGVfMTUxNjM1MTg2MTk3OSIsImNsbyI6IkpfZWxlX2Nsb18xNTE2MzUxODYxOTc5In0sInZhbHVlcyI6eyJpbWciOiIvL3p0Mm0uenRnYW1lLmNvbS91cGxvYWQvenQybS9pbWFnZXMvY2xwLzIwMTgtMDEvMTkvNWE2MWIxZTU5NGI4YS5naWYiLCJsaW5rIjoiIiwicG9zIjoibm8iLCJkaXN0IjoiMCJ9fSwiSl9wcm9zXzE1MTYzNTE5NjQ5NjMiOnsiY2FsbG5hbWUiOiJwYWdlSW1nIiwiaWRzIjp7InByb3MiOiJKX3Byb3NfMTUxNjM1MTk2NDk2MyIsImVsZSI6IkpfZWxlXzE1MTYzNTE5NjQ5NjMiLCJjbG8iOiJKX2VsZV9jbG9fMTUxNjM1MTk2NDk2MyJ9LCJ2YWx1ZXMiOnsiaW1nIjoiLy96dDJtLnp0Z2FtZS5jb20vdXBsb2FkL3p0Mm0vaW1hZ2VzL2NscC8yMDE4LTAxLzE5LzVhNjFiMWU0ZDZjYjcuanBnIiwibGluayI6IiIsInBvcyI6Im5vIiwiZGlzdCI6IjAifX0sIkpfcHJvc18xNTE2MzUyNzUzNjk5Ijp7ImNhbGxuYW1lIjoicGFnZUltZyIsImlkcyI6eyJwcm9zIjoiSl9wcm9zXzE1MTYzNTI3NTM2OTkiLCJlbGUiOiJKX2VsZV8xNTE2MzUyNzUzNjk5IiwiY2xvIjoiSl9lbGVfY2xvXzE1MTYzNTI3NTM2OTkifSwidmFsdWVzIjp7ImltZyI6Imh0dHA6Ly9jbHAuenRnYW1lLmNvbS9pbWFnZXMvY2FudmFzX2RpbWcucG5nIiwibGluayI6IiIsInBvcyI6Im5vIiwiZGlzdCI6IjAifX0sIkpfcHJvc18xNTE2MzUyNzk4MzE0Ijp7ImNhbGxuYW1lIjoicGFnZUltZyIsImlkcyI6eyJwcm9zIjoiSl9wcm9zXzE1MTYzNTI3OTgzMTQiLCJlbGUiOiJKX2VsZV8xNTE2MzUyNzk4MzE0IiwiY2xvIjoiSl9lbGVfY2xvXzE1MTYzNTI3OTgzMTQifSwidmFsdWVzIjp7ImltZyI6Ii8venQybS56dGdhbWUuY29tL3VwbG9hZC96dDJtL2ltYWdlcy9jbHAvMjAxOC0wMS8xOS81YTYxYjUzYTA1ZmVhLnBuZyIsImxpbmsiOiJodHRwOi8venQyY2hhbm5lbGRvd24uenRnYW1lLmNvbS5jbi8yODc1MTc5LmFwayIsInBvcyI6InRvcCIsImRpc3QiOiIwIn19LCJKX3Byb3NfMTUxNjM1MzAyNDQwNCI6eyJjYWxsbmFtZSI6InBhZ2VJbWciLCJpZHMiOnsicHJvcyI6IkpfcHJvc18xNTE2MzUzMDI0NDA0IiwiZWxlIjoiSl9lbGVfMTUxNjM1MzAyNDQwNCIsImNsbyI6IkpfZWxlX2Nsb18xNTE2MzUzMDI0NDA0In0sInZhbHVlcyI6eyJpbWciOiIvL3p0Mm0uenRnYW1lLmNvbS91cGxvYWQvenQybS9pbWFnZXMvY2xwLzIwMTgtMDEvMTkvNWE2MWI2MDYyMDQ4Yy5qcGciLCJsaW5rIjoiIiwicG9zIjoibm8iLCJkaXN0IjoiMCJ9fSwiSl9wcm9zXzE1MTYzNTQxOTc1NzgiOnsiY2FsbG5hbWUiOiJwYWdlSW1nIiwiaWRzIjp7InByb3MiOiJKX3Byb3NfMTUxNjM1NDE5NzU3OCIsImVsZSI6IkpfZWxlXzE1MTYzNTQxOTc1NzgiLCJjbG8iOiJKX2VsZV9jbG9fMTUxNjM1NDE5NzU3OCJ9LCJ2YWx1ZXMiOnsiaW1nIjoiLy96dDJtLnp0Z2FtZS5jb20vdXBsb2FkL3p0Mm0vaW1hZ2VzL2NscC8yMDE4LTAxLzE5LzVhNjFiYWFhNGIwNzkuZ2lmIiwibGluayI6IiIsInBvcyI6Im5vIiwiZGlzdCI6IjAifX0sIkpfcHJvc18xNTE2MzU0MjI1MzYyIjp7ImNhbGxuYW1lIjoicGFnZUltZyIsImlkcyI6eyJwcm9zIjoiSl9wcm9zXzE1MTYzNTQyMjUzNjIiLCJlbGUiOiJKX2VsZV8xNTE2MzU0MjI1MzYyIiwiY2xvIjoiSl9lbGVfY2xvXzE1MTYzNTQyMjUzNjIifSwidmFsdWVzIjp7ImltZyI6Ii8venQybS56dGdhbWUuY29tL3VwbG9hZC96dDJtL2ltYWdlcy9jbHAvMjAxOC0wMS8xOS81YTYxYmFiOTI2YTA3LmpwZyIsImxpbmsiOiIiLCJwb3MiOiJubyIsImRpc3QiOiIwIn19LCJKX3Byb3NfMTUxNjM1NDkyNDYzOSI6eyJjYWxsbmFtZSI6InBhZ2VJbWciLCJpZHMiOnsicHJvcyI6IkpfcHJvc18xNTE2MzU0OTI0NjM5IiwiZWxlIjoiSl9lbGVfMTUxNjM1NDkyNDYzOSIsImNsbyI6IkpfZWxlX2Nsb18xNTE2MzU0OTI0NjM5In0sInZhbHVlcyI6eyJpbWciOiIvL3p0Mm0uenRnYW1lLmNvbS91cGxvYWQvenQybS9pbWFnZXMvY2xwLzIwMTgtMDEvMTkvNWE2MWJkNzJiY2RlZC5wbmciLCJsaW5rIjoiIiwicG9zIjoidG9wIiwiZGlzdCI6IjAifX0sIkpfcHJvc18xNTE2MzU0OTMzNzUzIjp7ImNhbGxuYW1lIjoicGFnZUltZyIsImlkcyI6eyJwcm9zIjoiSl9wcm9zXzE1MTYzNTQ5MzM3NTMiLCJlbGUiOiJKX2VsZV8xNTE2MzU0OTMzNzUzIiwiY2xvIjoiSl9lbGVfY2xvXzE1MTYzNTQ5MzM3NTMifSwidmFsdWVzIjp7ImltZyI6Ii8venQybS56dGdhbWUuY29tL3VwbG9hZC96dDJtL2ltYWdlcy9jbHAvMjAxOC0wMS8xOS81YTYxYmQ3ZDBiMmI3LmpwZyIsImxpbmsiOiIiLCJwb3MiOiJubyIsImRpc3QiOiIwIn19LCJKX3Byb3NfMTUxNjM1NDk0MjIwNyI6eyJjYWxsbmFtZSI6InBhZ2VJbWciLCJpZHMiOnsicHJvcyI6IkpfcHJvc18xNTE2MzU0OTQyMjA3IiwiZWxlIjoiSl9lbGVfMTUxNjM1NDk0MjIwNyIsImNsbyI6IkpfZWxlX2Nsb18xNTE2MzU0OTQyMjA3In0sInZhbHVlcyI6eyJpbWciOiIvL3p0Mm0uenRnYW1lLmNvbS91cGxvYWQvenQybS9pbWFnZXMvY2xwLzIwMTgtMDEvMTkvNWE2MWJkOGZkZWNlZC5naWYiLCJsaW5rIjoiIiwicG9zIjoibm8iLCJkaXN0IjoiMCJ9fSwiSl9wcm9zXzE1MTYzNTQ5NjU0MTciOnsiY2FsbG5hbWUiOiJwYWdlSW1nIiwiaWRzIjp7InByb3MiOiJKX3Byb3NfMTUxNjM1NDk2NTQxNyIsImVsZSI6IkpfZWxlXzE1MTYzNTQ5NjU0MTciLCJjbG8iOiJKX2VsZV9jbG9fMTUxNjM1NDk2NTQxNyJ9LCJ2YWx1ZXMiOnsiaW1nIjoiLy96dDJtLnp0Z2FtZS5jb20vdXBsb2FkL3p0Mm0vaW1hZ2VzL2NscC8yMDE4LTAxLzE5LzVhNjFiZDk5NTFiMjIuanBnIiwibGluayI6IiIsInBvcyI6Im5vIiwiZGlzdCI6IjAifX0sIkpfcHJvc18xNTE2MzU1NTEzNDg0Ijp7ImNhbGxuYW1lIjoicGFnZUltZyIsImlkcyI6eyJwcm9zIjoiSl9wcm9zXzE1MTYzNTU1MTM0ODQiLCJlbGUiOiJKX2VsZV8xNTE2MzU1NTEzNDg0IiwiY2xvIjoiSl9lbGVfY2xvXzE1MTYzNTU1MTM0ODQifSwidmFsdWVzIjp7ImltZyI6Ii8venQybS56dGdhbWUuY29tL3VwbG9hZC96dDJtL2ltYWdlcy9jbHAvMjAxOC0wMS8xOS81YTYxYmZjMGRiMmZiLnBuZyIsImxpbmsiOiIiLCJwb3MiOiJubyIsImRpc3QiOiIwIn19LCJKX3Byb3NfMTUxNjM1NTc1MDQ3MSI6eyJjYWxsbmFtZSI6InBhZ2VJbWciLCJpZHMiOnsicHJvcyI6IkpfcHJvc18xNTE2MzU1NzUwNDcxIiwiZWxlIjoiSl9lbGVfMTUxNjM1NTc1MDQ3MSIsImNsbyI6IkpfZWxlX2Nsb18xNTE2MzU1NzUwNDcxIn0sInZhbHVlcyI6eyJpbWciOiIvL3BhZ2UuenRnYW1lLmNvbS91cGxvYWQvcGFnZS9pbWFnZXMvY2xwLzIwMTgtMDEvMTkvNWE2MWMwYjM5MWQ0MS5wbmciLCJsaW5rIjoiIiwicG9zIjoibm8iLCJkaXN0IjoiMCJ9fSwiSl9wcm9zXzE1MTYzNTU4OTUyOTUiOnsiY2FsbG5hbWUiOiJwYWdlSW1nIiwiaWRzIjp7InByb3MiOiJKX3Byb3NfMTUxNjM1NTg5NTI5NSIsImVsZSI6IkpfZWxlXzE1MTYzNTU4OTUyOTUiLCJjbG8iOiJKX2VsZV9jbG9fMTUxNjM1NTg5NTI5NSJ9LCJ2YWx1ZXMiOnsiaW1nIjoiJTJGJTJGcGFnZS56dGdhbWUuY29tJTJGdXBsb2FkJTJGcGFnZSUyRmltYWdlcyUyRmNscCUyRjIwMTgtMDElMkYxOSUyRjVhNjFjMTQwNjAwOTkucG5nIiwibGluayI6IiIsInBvcyI6Im5vIiwiZGlzdCI6IjAifX19';

$str = 'eyJKX3Byb3NfMTUxNjM1NTU5MDE2MCI6eyJjYWxsbmFtZSI6InBhZ2VJbWciLCJpZHMiOnsicHJvcyI6IkpfcHJvc18xNTE2MzU1NTkwMTYwIiwiZWxlIjoiSl9lbGVfMTUxNjM1NTU5MDE2MCIsImNsbyI6IkpfZWxlX2Nsb18xNTE2MzU1NTkwMTYwIn0sInZhbHVlcyI6eyJpbWciOiIvL3cxLmRldi56dGdhbWUuY29tL2ppZ3Nhdy91cGxvYWQvamlnc2F3L2ltYWdlcy8yMDE4LTAxLzE5LzVhNjFiZjk1Y2JiZGQucG5nIiwibGluayI6IiIsInBvcyI6Im5vIiwiZGlzdCI6IjAifX19';
$ret = base64_decode($str);
echo $ret;