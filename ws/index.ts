interface gameInfo {
  playerID: number;
  gameName: string;
  authKey: string;
}

const server = Bun.serve({
  fetch(req, server) {
    const url = new URL(req.url);
    if (url.pathname === "/gameUpdate") {
      this.publish("chat", "There is an update");
      return new Response("Handling Game Update");
    }
    if (url.pathname === "/ping") return new Response("pong!");
    if (url.pathname === "/ws") {
      console.log(`upgrade!`);
      // const username = getUsernameFromReq(req);
      const success = server.upgrade(req);
      return success
        ? undefined
        : new Response("Upgrade failed :(", { status: 500 });
    }

    return new Response("Hello world");
  },
  websocket: {
    // a message is received
    message(ws, message) {
      console.log(message);
    },
    // a socket is opened
    open(ws) {
      console.log(ws);
      ws.subscribe("chat");
    },
    // a socket is closed
    close(ws, code, message) {
      console.log(message), console.log(ws), console.log(code);
    },
    // the socket is ready to receive more data}, // handlers
    drain(ws) {
      console.log(ws);
    },
  },
});

console.log(`Listening on ${server.hostname}:${server.port}`);
