<?php

require_once __DIR__ . '/../LuaStandalone/LuaStandaloneEngine.php';

/*
 * this class for running the server with the lua binary
 */
class Scribunto_LuaRemoteBinary extends Scribunto_LuaStandaloneEngine {
	
	protected function newInterpreter() {
		return new Scribunto_LuaRemoteInterpreter( $this, $this->options );
	}
	
}

class Scribunto_LuaRemoteInterpreter extends Scribunto_LuaStandaloneInterpreter {
	
	/**
	 * override parent send message because it should be encoded message already
	 * @param $msg message to lua
	 */
	protected function sendMessage( $msg ) {
		global $wgScribuntoLuaRemoteDebug;
// 		$this->debug( "TX ==> {$msg['op']}" );
// 		$this->checkValid();
		// Send the message
// 		$encMsg = $this->encodeMessage( $msg );
		if ($wgScribuntoLuaRemoteDebug)
			parent::sendMessage($msg);
		else
			if ( !fwrite( $this->writePipe, $encMsg ) ) {
				// Write error, probably the process has terminated
				// If it has, handleIOError() will throw. If not, throw an exception ourselves.
				$this->handleIOError();
				throw $this->engine->newException( 'scribunto-luastandalone-write-error' );
			}
	}
	
	
}