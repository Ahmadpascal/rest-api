const API = import.meta.env.VITE_API_URL ?? "http://127.0.0.1:8000/api";
export async function getTodos() {
  const r = await fetch(`${API}/todos`);
  if (!r.ok) throw new Error("Gagal memuat data");
  return r.json();
}
export async function createTodo(title) {
  const r = await fetch(`${API}/todos`, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ title }),
  });
  if (!r.ok) throw new Error("Gagal menambah todo");
  return r.json();
}
export async function toggleTodo(id, completed) {
  const r = await fetch(`${API}/todos/${id}`, {
    method: "PATCH",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ completed }),
  });
  if (!r.ok) throw new Error("Gagal memperbarui todo");
  return r.json();
}
export async function deleteTodo(id) {
  const r = await fetch(`${API}/todos/${id}`, { method: "DELETE" });
  if (!r.ok) throw new Error("Gagal menghapus todo");
  return true;
}

// export async function updateTodo(id, title) {
//   const res = await fetch(`${API}/todos/${id}`, {
//     method: "PUT",
//     headers: {
//       "Content-Type": "application/json",
//       "Accept": "application/json",
//     },
//     body: JSON.stringify({ title }),
//   });

//   if (!res.ok) {
//     const errText = await res.text();
//     throw new Error("Gagal mengubah todo: " + errText);
//   }

//   return res.json();
// }

export async function updateTodo(id, title) {
  const res = await fetch(`${API}/todos/${id}`, {
    method: "PUT",
    headers: {
      "Content-Type": "application/json",
      Accept: "application/json",
    },
    body: JSON.stringify({ title }),
  });

  if (!res.ok) {
    const errText = await res.text();
    throw new Error("Gagal mengubah todo: " + errText);
  }

  return res.json();
}
